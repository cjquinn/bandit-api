<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
    <title><?= $this->fetch('title') ?></title>
</head>

<?php
    $h1Styles = "font-family: 'Helvetica Neue', 'Helvetica', sans-serif; font-weight: 500; font-size: 25px; color: #FFFFFF; line-height: 37px;";

    $h2Styles = "font-family: 'Helvetica Neue', 'Helvetica', sans-serif; font-weight: 500; font-size: 21px; color: #FFFFFF; line-height: 37px;";

    $pStyles = "font-family: 'Helvetica Neue', 'Helvetica', sans-serif; font-size: 17px; color: #ADC8FA; line-height: 25px;";

    $buttonAnchorStyles = "background-color:#1D2438; text-decoration: none; border:2px solid #F98646;border-radius:3px;display:block; height: 48px; width:100%;";

    $buttonTextSpanStyles = "display: block; width: 100%; border-top: 2px solid #626F94; line-height:42px; text-align:center; text-decoration: none; font-family: 'Helvetica Neue', 'Helvetica', sans-serif; font-weight: 500;font-size:18px;color:#fff;";
?>

<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" bgcolor="#000207" style="padding: 100px 0;">
        <tr>
            <td align="center" valign="top" cellpadding="0" style="padding: 0;">
                <!--[if (gte mso 9)|(IE)]><table width="600" align="center" cellpadding="0" cellspacing="0" border="0"><tr><td><![endif]-->
                    <div style="max-width: 440px; margin:0 auto;">

                        <table border="0" cellpadding="20" cellspacing="0" width="100%" bgcolor="#1D253E">

                            <tr>
                                <td align="center" valign="top" style="padding: 35px 0;">
                                    <img src="https://i.imgur.com/9z8x0GP.png" alt="Bandit Match" width="162" />
                                </td>
                            </tr>

                            <?= $this->fetch('content') ?>

                        </table>

                    </div>
                <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
