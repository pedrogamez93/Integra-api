<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body yahoo bgcolor="white">
  <table width="100%" bgcolor="white" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <!--COMIENZO TEMPLATE-->
        <!--[if (gte mso 9)|(IE)]>
						<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%" style="max-width:600px;">
							<tr>
							   <td align="center" style="max-width:600px; width:100%">
					<![endif]-->
        <table class="content" align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%;max-width: 600px;">
          <!--PRE HEADER-->
          
          <!--HEADER-->
          <tr>
            <td align="center" valign="top" id="templateHeader">
              <!--[if (gte mso 9)|(IE)]>
									<table>
										<tr>
											<td border="0" cellpadding="0" cellspacing="0" width="100%">
									<![endif]-->

              <!--Tabla Imagen-->
              <br>
              <img src="https://s3.amazonaws.com/cdn.meat.cl/mailing/integra/logo+integra.png" alt="logointegra">
              
              <!--[if (gte mso 9)|(IE)]>
									</td>
									</tr>
									</table>
									<![endif]-->
            </td>
          </tr>
          <tr>
              <td height="25px">
                <img src="https://gallery.mailchimp.com/d11da196fd6ec7bb3070e74be/images/87a32646-1e92-4420-b454-cc905f24c443.png" alt=""
                  width="1px" height="25px">
              </td>
            </tr>
          <!--HEADER-->

          <tr>
              <td align="center" valign="top" id="templateHeader">
                <!--[if (gte mso 9)|(IE)]>
                    <table>
                      <tr>
                        <td border="0" cellpadding="0" cellspacing="0" width="100%" >
                    <![endif]-->
  
                <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>
                    <td width="12%">&nbsp;</td>
                    <td valign="top" class="tdestilotitulo" style="text-align: left; font-family:Helvetica, sans-serif; font-size: 22px;">
                      
                      <p style="font-family: Arial, Helvetica, sans-serif; font-size:15px; color:rgb(120, 120, 121); line-height:160%;">
                      Estimado/a trabajador/a, {{ $data['first_name'] }} {{ $data['last_name'] }}, 
                      {{$data['rut']}}, agradecemos su donación realizada a la campaña: 
                      {{$data['title']}}, realizada el día {{$data['date']}} a las 
                      {{$data['time']}}, por un monto de $ {{$data['amount']}}.<br>
                      </p>
                      <p style="font-family: Arial, Helvetica, sans-serif; font-size:15px; color:rgb(120, 120, 121); line-height:160%;">
                        Atentamente, <br><br>
                          Fundación Integra <br><br>
                          *Por favor no responda a esta casilla, este correo se genera automáticamente.<br>
                          *Para consultas contacte al Departamento de Gestión Social y Beneficios del Área Social y Calidad de Vida de la Dirección Nacional de Personas, al correo electrónico: {{$data['email_constribution']}}
                    </td>
                    <td width="12%">&nbsp;</td>
                  </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
                    </td>
                    </tr>
                    </table>
                    <![endif]-->
              </td>
            </tr>
          <tr>
            <td height="25px">
              <img src="https://gallery.mailchimp.com/d11da196fd6ec7bb3070e74be/images/87a32646-1e92-4420-b454-cc905f24c443.png" alt=""
                width="1px" height="25px">
            </td>
          </tr>
          <tr>
              <!--[if (gte mso 9)|(IE)]>
                          </td>
                        </tr><tr>
                  <td>
                    <img src="https://gallery.mailchimp.com/d11da196fd6ec7bb3070e74be/images/87a32646-1e92-4420-b454-cc905f24c443.png" alt="" width="10px" height="10px">
                  </td>
                </tr>
                      </table>
                    <![endif]-->
            </td>
          </tr>
        </table>
        <!--[if (gte mso 9)|(IE)]>
						</td>
						   </tr>
							   </table>
					<![endif]-->
        <!--FIN TEMPLATE-->
      </td>
    </tr>
  </table>
</body>

</html>
