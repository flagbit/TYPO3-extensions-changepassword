<?php
namespace Flagbit\FbChangepassword\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Vanessa Grunz <vanessa.grunz@flagbit.de>, Flagbit GmbH & Co. KG
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * FrontendUserController
 */

class FrontendUserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * @var \Flagbit\FbChangepassword\Domain\Repository\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository;

    /**
     * Currently logged in frontendUser
     *
     * @var FrontendUser
     */
    protected $loggedInFrontendUser;

    /**
     * InitializeAction
     *
     * @return void
     */
    public function initializeAction() {
        $frontendUserUid = $GLOBALS['TSFE']->fe_user->user['uid'];
        $this->loggedInFrontendUser = $this->frontendUserRepository->findByUid($frontendUserUid);
    }


    /**
     * @return \TYPO3\Flow\Error\Message
     */
    protected function getErrorFlashMessage() {
        switch ($this->actionMethodName) {
            case 'saveAction' :
                return false;
            default:
                return parent::getErrorFlashMessage();
        }
    }


    /**
     * action list
     *
     * @return void
     */
    public function listAction() {
        $this->view->assign('frontendUser', $this->loggedInFrontendUser);
    }

    /**
     *  Save data
     *
     * @param array $newPassword
     * @validate $newPassword \Flagbit\FbChangepassword\Validation\Validator\PasswordValidator
     * @return void
     */
    public function saveAction(array $newPassword = NULL) {
        $instanceSalted = \TYPO3\CMS\Saltedpasswords\Salt\SaltFactory::getSaltingInstance();
        $newPassword = $instanceSalted->getHashedPassword($newPassword[2]);
        $this->loggedInFrontendUser->setPassword($newPassword);
        $this->frontendUserRepository->update($this->loggedInFrontendUser);
    }

}