function EmailTemplate({ text1, text2, link , logo }) {
    return `
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset='utf-8' />
                <link rel='stylesheet' href='./css/app.min.css' />
                <link rel='stylesheet' href='./css/icons.min.css' />
                <link rel='stylesheet' href='./css/bootstrap.min.css' />
                <script src='script.js'></script>
            </head>
            <body style='display: flex; justify-content: center; align-items: flex-start; height: 100vh; margin: 0;'>
                <div>
                    <table class='body-wrap'>
                        <tr>
                            <td valign='top'></td>
                            <td class='container' width='600' valign='top'>
                                <div class='content' style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;'>
                                    <table class='main' width='100%' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td class='content-wrap' style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0; padding: 30px; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); border-radius: 7px; background-color: #fff;' valign='top'>
                                                <meta itemprop='name' content='Confirm Email' />
                                                <table width='100%' cellpadding='0' cellspacing='0'>

                                                ${text1 ? `
                                                    <tr>
                                                        <td class='content-block' style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>
                                                            ${text1}
                                                        </td>
                                                    </tr>
                                                    ` : ""}
                                                    ${text2 ? `
                                                    <tr>
                                                        <td class='content-block' style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>
                                                            ${text2}
                                                        </td>
                                                    </tr>
                                                    ` : ""}
                                                    ${link ? `
                                                    <tr style='margin: 4px 2px;'>
                                                        <td class='content-block' itemprop='handler' itemscope style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>
                                                            ${link}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class='content-block' style='font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;' valign='top'>
                                                            <b>Safesecur</b>
                                                            <p>Equipe support</p>
                                                        </td>
                                                    </tr>
                                                    ` : ""}
                                                    <tr>
                                                        <td class='content-block' style='text-align: center; font-family: Helvetica Neue, Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0;' valign='top'>
                                                            Safesecur © ${new Date().getFullYear()} - Tout droit réservé.
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div align='center'>
                        <img src="${logo}" height='50' alt="Safesecur" style="max-width: 200px; height: auto;"/>
                    </div>
                </div>
            </body>
        </html>
    `;
}

// module.exports = EmailTemplate;
export default EmailTemplate;