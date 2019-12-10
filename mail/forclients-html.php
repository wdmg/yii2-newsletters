<?php
use yii\helpers\Html;
use yii\helpers\Url;
use wdmg\newsletters\mail\NewslettersAsset;

$bundle = NewslettersAsset::register($this);


if (isset(Yii::$app->mails))
    $logotype_url = Yii::$app->mails->getTrackingUrl($bundle->baseUrl . '/images/logotype-white-en.png');
else
    $logotype_url = $bundle->baseUrl . '/images/logotype-white-en.png';

if (isset(Yii::$app->mails))
    $webversion_url = Yii::$app->mails->getWebversionUrl();
else
    $webversion_url = null;

?>

<div class="es-wrapper-color" style="background-color:#F4F4F4;">
    <!--[if gte mso 9]>
    <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
        <v:fill type="tile" color="#f4f4f4"></v:fill>
    </v:background>
    <![endif]-->
    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;">
        <tr class="gmail-fix" height="0" style="border-collapse:collapse;">
            <td style="padding:0;margin:0;"></td>
        </tr>
        <tr style="border-collapse:collapse;">
            <td valign="top" style="padding:0;margin:0;">
                <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                    <tr style="border-collapse:collapse;"></tr>
                    <tr style="border-collapse:collapse;">
                        <td align="center" style="padding:0;margin:0;">
                            <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center">
                                <tr style="border-collapse:collapse;">
                                    <td align="left" style="margin:0;padding-left:10px;padding-right:10px;padding-top:15px;padding-bottom:15px;">
                                        <!--[if mso]><table width="580" cellpadding="0" cellspacing="0"><tr><td width="282" valign="top"><![endif]-->
                                        <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                                            <tr style="border-collapse:collapse;">
                                                <td width="282" align="left" style="padding:0;margin:0;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td class="es-infoblock es-m-txt-c" align="left" style="padding:0;margin:0;line-height:14px;font-size:12px;color:#CCCCCC;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:14px;color:#696969;">🚀Хорошо делаем отличные сайты!</p></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table>
                                        <!--[if mso]></td><td width="20"></td><td width="278" valign="top"><![endif]-->
                                        <table class="es-right" cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;">
                                            <tr style="border-collapse:collapse;">
                                                <td width="278" align="left" style="padding:0;margin:0;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td class="es-infoblock es-m-txt-c" align="right" style="padding:0;margin:0;line-height:14px;font-size:12px;color:#CCCCCC;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:14px;color:#696969;">
                                                                <?php if ($webversion_url) { ?>
                                                                        Не видно картинок? &nbsp;<a href="<?= $webversion_url; ?>" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:12px;text-decoration:underline;color:#333333;text-align:center;">Веб-версия письма &gt;</a>
                                                                <?php } ?>
                                                                </p></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table>
                                        <!--[if mso]></td></tr></table><![endif]--></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:#059CBD;background-image:url('<?= $bundle->baseUrl . '/images/waves-bg-light.png'; ?>');background-repeat:no-repeat;background-size:cover;background-position:center center;">
                    <tr><td>
                            <table class="es-header" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                                <tr style="border-collapse:collapse;">
                                    <td style="padding:0;margin:0;background-color:transparent;" bgcolor="transparent" align="center">
                                        <table class="es-header-body" width="600" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td width="580" valign="top" align="center" style="padding:0;margin:0;">
                                                                <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                                    <tr style="border-collapse:collapse;">
                                                                        <td align="center" style="margin:0;padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;"><a href="https://wdmg.com.ua/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=header-logo" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $logotype_url; ?>" alt="W.D.M.Group, Украина" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;" title="W.D.M.Group, Украина" height="60"></a></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table>
                            <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                                <tr style="border-collapse:collapse;">
                                    <td style="padding:0;margin:0;background-color:transparent;" bgcolor="transparent" align="center">
                                        <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center">
                                            <tr style="border-collapse:collapse;">
                                                <td align="left" style="padding:0;margin:0;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td width="600" valign="top" align="center" style="padding:0;margin:0;">
                                                                <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#FFFFFF;border-radius:4px;margin-bottom: -20px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                                                    <tr style="border-collapse:collapse;">
                                                                        <td align="center" style="margin:0;padding-bottom:10px;padding-top:15px;padding-left:30px;padding-right:30px;"><h1 style="margin:0;line-height:31px;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:26px;font-style:normal;font-weight:normal;color:#696969;">Приветствуем!</h1></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table>
                        </td></tr>
                </table>
                <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;margin-top: 20px;">
                    <tr style="border-collapse:collapse;">
                        <td align="center" style="padding:0;margin:0;">
                            <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center">
                                <tr style="border-collapse:collapse;border-top-width:3px;border-top-style:solid;border-top-color:#FF8A00;">
                                    <td align="left" style="padding:0;margin:0;">
                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td width="600" valign="top" align="center" style="padding:0;margin:0;">
                                                    <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:4px;background-color:#FFFFFF;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                                                        <tr style="border-collapse:collapse;">
                                                            <td class="es-m-txt-l" align="left" style="margin:0;padding-top:20px;padding-left:30px;padding-right:30px;padding-bottom:40px;">
                                                                <?= $content; ?>
                                                            </td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                    <tr style="border-collapse:collapse;">
                        <td align="center" style="padding:0;margin:0;">
                            <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center">
                                <tr style="border-collapse:collapse;">
                                    <td align="left" style="padding:0;margin:0;">&nbsp;</td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
                    <tr style="border-collapse:collapse;">
                        <td align="center" style="padding:0;margin:0;">
                            <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center">
                                <tr style="border-collapse:collapse;">
                                    <td align="left" style="padding:0;margin:0;">
                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td width="600" valign="top" align="center" style="padding:0;margin:0;">
                                                    <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;background-color:#0A9EBF;border-top-left-radius:4px;border-top-right-radius:4px;border-bottom-right-radius:4px;border-bottom-left-radius:4px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#0A9EBF">
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="center" style="padding:0;margin:0;padding-top:30px;padding-left:30px;padding-right:30px;"><h3 style="margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#FFFFFF;">Остались вопросы?</h3></td>
                                                        </tr>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="center" style="padding:10px;margin:0;"><span class="es-button-border" style="border-style:solid;border-color:#539BE2;background:#539BE2;border-width:1px;display:inline-block;border-radius:2px;width:auto;background-color:#FF8A00;background-position:initial initial;background-repeat:initial initial;"><a href="https://wdmg.com.ua/contacts/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=feedback" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:20px;color:#FFFFFF;border-style:solid;border-color:#FF8A00;border-width:15px 25px 15px 25px;display:inline-block;background:#539BE2;border-radius:12px;font-weight:normal;font-style:normal;line-height:24px;width:auto;text-align:center;background-color:#FF8A00;background-position:initial initial;background-repeat:initial initial;">Мы на связи!</a></span></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>
                    </tr>
                </table>
                <table class="es-footer" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;">
                    <tr style="border-collapse:collapse;">
                        <td align="center" style="padding:0;margin:0;">
                            <table class="es-footer-body" width="600" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;">
                                <tr style="border-collapse:collapse;">
                                    <td align="left" style="padding:0;margin:0;padding-top:20px;padding-left:30px;padding-right:30px;">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td width="540" align="center" valign="top" style="padding:0;margin:0;">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="center" style="padding:0;margin:0;">
                                                                <table cellpadding="0" cellspacing="0" class="es-table-not-adapt es-social" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                                    <tr style="border-collapse:collapse;">
                                                                        <td align="center" valign="top" style="padding:0;margin:0;padding-right:10px;"><a target="_blank" href="https://www.facebook.com/wdmgroup/" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $bundle->baseUrl . '/images/facebook-circle-colored.png'; ?>" alt="Fb" title="Facebook" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                                        <td align="center" valign="top" style="padding:0;margin:0;padding-right:10px;"><a target="_blank" href="https://vk.com/wdmgroup" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $bundle->baseUrl . '/images/vk-circle-colored.png'; ?>" alt="VK" title="Vkontakte" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                                        <td align="center" valign="top" style="padding:0;margin:0;padding-right:10px;"><a target="_blank" href="https://twitter.com/wdmg_eu" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $bundle->baseUrl . '/images/twitter-circle-colored.png'; ?>" alt="Tw" title="Twitter" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                                        <td align="center" valign="top" style="padding:0;margin:0;padding-right:10px;"><a target="_blank" href="https://www.instagram.com/wdm.group/" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $bundle->baseUrl . '/images/instagram-circle-colored.png'; ?>" alt="Ig" title="Instagram" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                                        <td align="center" valign="top" style="padding:0;margin:0;padding-right:10px;"><a target="_blank" href="https://www.linkedin.com/company/wdmg" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $bundle->baseUrl . '/images/linkedin-circle-colored.png'; ?>" alt="In" title="Linkedin" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                                        <td align="center" valign="top" style="padding:0;margin:0;"><a target="_blank" href="https://www.behance.net/wdmg" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;"><img src="<?= $bundle->baseUrl . '/images/behance-circle-colored.png'; ?>" alt="Be" title="Behance" width="32" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></a></td>
                                                                    </tr>
                                                                </table></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                                <tr style="border-collapse:collapse;">
                                    <td align="left" style="margin:0;padding-top:30px;padding-bottom:30px;padding-left:10px;padding-right:10px;">
                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td width="540" valign="top" align="center" style="padding:0;margin:0;">
                                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="left" style="padding:0;margin:0;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#696969;"><strong><a target="_blank" href="https://wdmg.com.ua/about/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">О студии</a> - <a target="_blank" href="https://wdmg.com.ua/news/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">Новости</a> - <a target="_blank" href="https://wdmg.com.ua/uslugi/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">Услуги</a>&nbsp;- <a target="_blank" href="https://wdmg.com.ua/works/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">Портфолио</a> - <a target="_blank" href="https://wdmg.com.ua/contacts/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">Контакты</a></strong></p></td>
                                                        </tr>
                                                        <?php if ($webversion_url) { ?>
                                                            <tr style="border-collapse:collapse;">
                                                                <td align="left" style="padding:0;margin:0;padding-top:25px;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#696969;">Письмо отображается не корректно, попробуйте <a target="_blank" href="<?= $webversion_url; ?>" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">открыть его в браузере</a>.</p></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php if (isset($unsubscribe_url)) { ?>
                                                            <tr style="border-collapse:collapse;">
                                                                <td align="left" style="padding:0;margin:0;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#696969;">Если данная рассылка не желательна, вы можете <a target="_blank" href="<?= $unsubscribe_url; ?>" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">отписаться от неё</a> и мы больше не потревожим Вас.</p></td>
                                                            </tr>
                                                        <?php } ?>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="left" style="padding:0;margin:0;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#696969;">Если данная рассылка не желательна, вы можете <a target="_blank" href="" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">отписаться от неё</a> и мы больше не потревожим Вас.</p></td>
                                                        </tr>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="center" style="padding:0;margin:0;padding-top:15px;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#696969;">
                                                                    <a target="_blank" href="https://wdmg.com.ua/privacy/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">Политика конфиденциальности</a> | <a target="_blank" href="https://wdmg.com.ua/terms/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">Условия использования</a> | <a target="_blank" href="https://wdmg.com.ua/gdpr/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=footer" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">GDPR</a>
                                                                </p></td>
                                                        </tr>
                                                        <tr style="border-collapse:collapse;">
                                                            <td align="left" style="padding:0;margin:0;padding-top:25px;"><p style="margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;line-height:21px;color:#696969;">© 2006-2019 <a target="_blank" href="https://wdmg.com.ua/?utm_source=maillist&utm_medium=email&utm_campaign=newsletter&utm_content=copyrights" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:lato, 'helvetica neue', helvetica, arial, sans-serif;font-size:14px;text-decoration:underline;color:#111111;">W.D.M.Group, Ukraine</a>. All rights reserved.</p></td>
                                                        </tr>
                                                    </table></td>
                                            </tr>
                                        </table></td>
                                </tr>
                            </table></td>
                    </tr>
                </table></td>
        </tr>
    </table>
</div>




