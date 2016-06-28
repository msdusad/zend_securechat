<?php
namespace ScnSocialAuth\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SocialSignInButton extends AbstractHelper
{
    public function __invoke($provider, $redirect = false)
    {
        $redirectArg = $redirect ? '?redirect=' . $redirect : '';
        echo
            '<li class="sprites social-media-'.strtolower($provider).'"><a class="social '.$provider.'" href="'
            . $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider))
            . $redirectArg . '">' . ucfirst($provider) . '</a></li>';
    }
}
