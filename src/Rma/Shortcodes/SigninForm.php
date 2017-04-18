<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rma\Shortcodes;

/**
 * Description of SigninForm
 *
 * @author George
 */
class SigninForm
{
    static function createSignInForm() {
        $form = <<<EOT
<div class="panel panel-info">
    <div class="panel-heading">Member sign-in</div>
    <div class="panel-body">
        <form action=""  method="post">
            <input  type="hidden" name="action" value="member_sign_in">
            <div class="row">
                <div class="col-md-4">Email address</div>
                <div class="col-md-3"><input type="text" name="_email" /></div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-4">Password</div>
                <div class="col-md-3"><input type="password" name="_password" /></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4"><input type="submit" value="Sign in" /></div>
            </div>
        </form>
    </div>
</div>
EOT;
        return $form;
    }
}
