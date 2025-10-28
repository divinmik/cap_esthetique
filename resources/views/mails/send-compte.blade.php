<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>{{ env('APP_NAME') }}</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!--[if mso]>
    <style type="text/css">
      body, table, td {font-family: Arial, sans-serif !important;}
    </style>
  <![endif]-->
</head>
<body style="margin:0; padding:0; background:#ffffff; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

  <!-- Preheader (masqué) -->
  <div style="display:none; overflow:hidden; line-height:1px; opacity:0; max-height:0; max-width:0;">
    Vos accès à {{ env('APP_NAME') }} — conservez cet e-mail.
  </div>

  <!-- Wrapper -->
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#ffffff;">
    <tr>
      <td align="center" style="padding:24px 12px;">
        <!-- Container -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px; border:1px solid #f2f2f2; border-radius:12px; overflow:hidden;">
          <!-- Header -->
          <tr>
            <td align="center" style="padding:20px 16px; background:#fff7f5; border-bottom:1px solid #ffe6df;">
              <img src="{{ asset('admin/assets/images/logo-dark.png') }}" alt="Logo {{ env('APP_NAME') }}" width="56" height="56" style="display:block; border:0; outline:none; text-decoration:none;">
              <div style="font-family:Arial,Helvetica,sans-serif; font-size:18px; line-height:26px; color:#d1382a; font-weight:bold; margin-top:8px;">
                Bienvenue chez {{ env('APP_NAME') }}
              </div>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:24px 20px; background:#ffffff;">
              <h1 style="margin:0 0 12px 0; font-family:Arial,Helvetica,sans-serif; font-size:22px; line-height:30px; color:#1f2937; font-weight:700;">
                Vos informations de compte
              </h1>

              <p style="margin:0 0 16px 0; font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#374151;">
                Voici les détails associés à votre compte :
              </p>

              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 16px 0;">
                <tr>
                  <td style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#111827; padding:8px 0; border-bottom:1px solid #f3f4f6; width:160px;"><strong>E-mail</strong></td>
                  <td style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#374151; padding:8px 0; border-bottom:1px solid #f3f4f6;">
                    {{ $mailData['email'] }}
                  </td>
                </tr>
                <tr>
                  <td style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#111827; padding:8px 0; border-bottom:1px solid #f3f4f6;"><strong>Nom &amp; prénom</strong></td>
                  <td style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#374151; padding:8px 0; border-bottom:1px solid #f3f4f6;">
                    {{ $mailData['fullname'] }}
                  </td>
                </tr>
                <tr>
                  <td style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#111827; padding:8px 0;"><strong>Mot de passe</strong></td>
                  <td style="font-family:Arial,Helvetica,sans-serif; font-size:14px; line-height:22px; color:#374151; padding:8px 0;">
                    {{ $mailData['pwd'] }}
                  </td>
                </tr>
              </table>

              <!-- Bouton (rouge -> orange) -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="left" style="margin:8px 0 0 0;">
                <tr>
                  <td align="center" bgcolor="#e63946" style="border-radius:6px;">
                    <!--[if mso]>
                    <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word"
                      href="{{ env('APP_URL') }}" style="height:44px;v-text-anchor:middle;width:280px;" arcsize="12%" fillcolor="#e63946" stroked="f">
                      <w:anchorlock/>
                      <center style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:16px;font-weight:bold;">
                        Accéder à mon compte
                      </center>
                    </v:roundrect>
                    <![endif]-->
                    <!--[if !mso]><!-- -->
                    <a href="{{ env('APP_URL') }}/login" target="_blank" rel="noopener"
                       style="display:inline-block; padding:12px 20px; font-family:Arial,Helvetica,sans-serif; font-size:16px; font-weight:bold; line-height:20px; color:#ffffff; text-decoration:none; background:linear-gradient(90deg,#e63946,#ff7a00); border-radius:6px;">
                      Accéder à mon compte
                    </a>
                    <!--<![endif]-->
                  </td>
                </tr>
              </table>

              <p style="clear:both; margin:16px 0 0 0; font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#6b7280;">
                Si le bouton ne fonctionne pas, copiez cette adresse dans votre navigateur : <br>
                <span style="word-break:break-all; color:#ef4444;">{{ env('APP_URL') }}/login</span>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="padding:18px 16px; background:#fff7f5; border-top:1px solid #ffe6df;">
              <p style="margin:0; font-family:Arial,Helvetica,sans-serif; font-size:12px; line-height:18px; color:#9ca3af;">
                © {{ date('Y') }} {{ env('APP_NAME') }} · Tous droits réservés
              </p>
            </td>
          </tr>
        </table>
        <!-- /Container -->
      </td>
    </tr>
  </table>
  <!-- /Wrapper -->
</body>
</html>
