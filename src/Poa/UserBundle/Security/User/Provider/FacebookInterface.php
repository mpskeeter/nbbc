<?php

/*
 * This file is part of the HWIOAuthBundle package.
 *
 * (c) Hardware.Info <opensource@hardware.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use HWI\Bundle\OAuthBundle\OAuth\ResourceOwner\FacebookResourceOwner;

/**
 * FacebookResourceOwner
 *
 * @author Geoffrey Bachelet <geoffrey.bachelet@gmail.com>
 */
class FacebookInterface extends FacebookResourceOwner
{
    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url'   => 'https://www.facebook.com/dialog/oauth',
        'access_token_url'    => 'https://graph.facebook.com/oauth/access_token',
        'infos_url'           => 'https://graph.facebook.com/me',
        'scope'               => '',
        'user_response_class' => '\HWI\Bundle\OAuthBundle\OAuth\Response\AdvancedPathUserResponse',
    );

    /**
     * {@inheritDoc}
     */
    protected $paths = array(
        'identifier'     => 'id',
        'nickname'       => 'username',
        'realname'       => 'name',
		'email'          => 'email',
		'profilepicture' => 'picture',
		'first_name'     => 'first_name',
		'last_name'      => 'last_name',
		'gender'         => 'gender',
		'locale'         => 'locale'
    );

    /**
     * Facebook unfortunately breaks the spec by using commas instead of spaces
     * to separate scopes
     */
    public function configure()
    {
        $this->options['scope'] = str_replace(',', ' ', $this->options['scope']);
    }
}
