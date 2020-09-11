<?php

namespace App\Controller\API;

use App\Entity\MobileAuth;
use App\Entity\MobileAuthCode;
use App\Repository\MobileAuthCodeRepository;
use App\Repository\MobileAuthRepository;
use App\Utils\MobileAuthAuthenticator;
use App\Utils\MobileAuthCodeAuthenticator;
use App\Utils\MobileAuthCodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;

/**
 *
 * Moviecontroller.
 * @Route("/api",name="mobileAuthCodeControllerAPI")
 */
class MobileAuthCodeAPIController extends AbstractController
{

    /**
     * @SWG\Info(title="2FactorAuthPHP", version="0.1")
     */
    /**
     *  Generate a mobile phone auth object if not exists and assigns a new mobile auth code.
     * @SWG\Post(
     *     path="/api/generate_code",
     *     summary="Generate a mobile phone auth object if not exists and assigns a new mobile auth code",
     * )
     * @SWG\Parameter(
     *     name="mobile",
     *     in="body",
     *     description="Requested mobile to generate a new auth code.",
     *     required=true,
     *     @SWG\Schema(@SWG\Property(property="mobile", type="string", example="666 55 99 75")),
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns generated code requested with mobile phone."
     *     ),
     * @SWG\Response(
     *         response=404,
     *         description="Invalid mobile phone."
     *     ),
     * @SWG\Response(
     *         response=500,
     *         description="Invalid mobile phone extension (Must contain 9 numbers)"
     *     )
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MobileAuthRepository $mobileAuthRepository
     * @param MobileAuthCodeRepository $mobileAuthCodeRepository
     * @return JsonResponse
     * @Route("/generate_code", name="generateMobileAuthCode", methods={"POST"})
     */
    public function generateMobileAuthCode(Request $request,
                                           EntityManagerInterface $entityManager,
                                           MobileAuthRepository $mobileAuthRepository)
    {
        try{
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('mobile')) throw new Exception('Invalid mobile phone.',404);


            $mobilePhone = $request->get('mobile');

            $isValidPhone = MobileAuthAuthenticator::checkMobilePhoneFormat($mobilePhone);


            if($isValidPhone == 1){

                $verificationCode = MobileAuthCodeGenerator::generateUniqueCode();
                $mobileAuthCodeObject = new MobileAuthCode();
                $mobileAuthCodeObject->setCode($verificationCode);
                $entityManager->persist($mobileAuthCodeObject);
                $entityManager->flush();

                /** @var MobileAuth $mobileAuthObject */
                $mobileAuthObject = $mobileAuthRepository->findOneByMobileNumber(trim($mobilePhone));


                if($mobileAuthObject == null){
                    $mobileAuthObject = new MobileAuth($mobilePhone, $mobileAuthCodeObject);
                    $entityManager->persist($mobileAuthObject);
                    $entityManager->flush();
                }else{
                    /** @var MobileAuthCode $lastMobileAuthCodeObject */
                    $lastMobileAuthCodeObject = $mobileAuthObject->getMobileAuthCode();

                    $lastMobileAuthCodeObject->setExpired(true);
                    $entityManager->persist($lastMobileAuthCodeObject);
                    $entityManager->flush();

                    $mobileAuthObject->setMobileAuthCode($mobileAuthCodeObject);
                    $entityManager->persist($mobileAuthObject);
                    $entityManager->flush();
                }

                return new JsonResponse($verificationCode);
            }
        }catch (\Exception $e){
            return new JsonResponse(array(
                "message" => $e->getMessage()
            ),$e->getCode());
        }
    }

    /**
     *
     * Check input mobile auth code and verify if it's valid.
     *
     *  @SWG\Post(path="/api/check_code",summary="Check input mobile auth code and verify if it's valid.",)
     *  @SWG\Parameter(
     *     name="mobile",
     *     in="body",
     *     description="Check if auth code is valid and shows result",
     *     required=true,
     *     @SWG\Schema(@SWG\Property(property="code", type="string", example="Y6F5")),
     *  )
     *  @SWG\Response(
     *      response=200,
     *      description="Return a message showing the mobile phone was succesfully authenticated."
     *  ),
     *  @SWG\Response(
     *      response=404,
     *      description="Authentification code not found. or Invalid authentication code. or Mobile phone not founded with this authentication code."
     *  ),
     *  @SWG\Response(
     *      response=500,
     *      description="Authorization code has expired."
     *  )
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MobileAuthCodeRepository $mobileAuthCodeRepository
     * @param MobileAuthRepository $mobileAuthRepository
     * @return JsonResponse
     * @Route("/check_code", name="checkMobileAuthCode", methods={"POST"})
     */
    public function checkMobileAuthCode(Request $request,
                                        EntityManagerInterface $entityManager,
                                        MobileAuthCodeRepository $mobileAuthCodeRepository,
                                        MobileAuthRepository $mobileAuthRepository){

        $mobileAuthCodeObject = null;
        try{
            $request = $this->transformJsonBody($request);

            if (!$request || !$request->get('code')) throw new Exception('Invalid authentication code.',404);

            $authenticationCode = $request->get('code');

            /** @var MobileAuthCode $mobileAuthCodeObject */
            $mobileAuthCodeObject = $mobileAuthCodeRepository->findOneByCode($authenticationCode);

            if($mobileAuthCodeObject != null){

                $isValidPhone = MobileAuthCodeAuthenticator::checkAuthenticationCode($mobileAuthCodeObject);

                if($isValidPhone){

                    /** @var MobileAuth $mobileAuthObject */
                    $mobileAuthObject = $mobileAuthRepository->findOneByMobileAuthCode($mobileAuthCodeObject);

                    if($mobileAuthObject != null){

                        $mobileAuthCodeObject->setExpired(true);
                        $entityManager->persist($mobileAuthCodeObject);
                        $entityManager->flush();

                        return new JsonResponse(array('message' => 'Mobile phone '.$mobileAuthObject->getMobileNumber()." authenticated sucessfully!"),200);
                    }else{
                        throw new Exception("Mobile phone not founded with this authentication code.",404);
                    }
                }
            }else{
                throw new Exception("Authentification code not found.",404);
            }

        }catch (\Exception $e){

            $code = $e->getCode() == 0 ? 404 : $e->getCode();
            return new JsonResponse(array(
                "message" => $e->getMessage()
            ),$code);
        }
    }


     protected function transformJsonBody(Request $request)
        {
            $data = json_decode($request->getContent(), true);

            if ($data === null) {
                return $request;
            }

            $request->request->replace($data);

            return $request;
        }

}