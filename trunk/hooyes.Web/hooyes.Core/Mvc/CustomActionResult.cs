using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
using System.Web;
using System.Drawing;

namespace hooyes.Core.Mvc
{
    public class CustomActionResult:ActionResult
    {

        public override void ExecuteResult(ControllerContext context)
        {
            context.HttpContext.Response.Write(context.HttpContext.Request.QueryString);
        }


    }
    public class CodeResult : ActionResult
    {

        public override void ExecuteResult(ControllerContext context)
        {
            CreateCheckCodeImage(context.HttpContext.Request.QueryString.Get("img"), context.HttpContext.Response);
        }

        private string GenerateCheckCode()
        {
            int number;
            char code;
            string checkCode = String.Empty;

            System.Random random = new Random();

            for (int i = 0; i < 5; i++)
            {
                number = random.Next();

                if (number % 2 == 0)
                    code = (char)('1' + (char)(number % 9));
                else
                    code = (char)('A' + (char)(number % 26));
                checkCode += code.ToString();
            }

            //Response.Cookies.Add(new HttpCookie(LevenWeb.Utility.Fetch.CookieName+"CheckCode", checkCode));
            //LevenWeb.Utility.SessionState.Set("CheckCode", checkCode);

            return checkCode;
        }

        private void CreateCheckCodeImage(string checkCode, HttpResponseBase response)
        {
            if (checkCode == null || checkCode.Trim() == String.Empty)
                return;

            System.Drawing.Bitmap image = new System.Drawing.Bitmap((int)Math.Ceiling((checkCode.Length * 12.5)), 22);
            Graphics g = Graphics.FromImage(image);

            try
            {
                //生成随机生成器
                Random random = new Random();

                //清空图片背景色
                g.Clear(Color.White);
                Pen drawPen = new Pen(Color.Silver);
                //画图片的背景噪音线
                for (int i = 0; i < 10; i++)
                {
                    int x1 = random.Next(image.Width);
                    int x2 = random.Next(image.Width);
                    int y1 = random.Next(image.Height);
                    int y2 = random.Next(image.Height);


                    g.DrawLine(drawPen, x1, y1, x2, y2);

                }
                drawPen.Dispose();
                Font font = new System.Drawing.Font("Arial", 13, (System.Drawing.FontStyle.Bold));
                System.Drawing.Drawing2D.LinearGradientBrush brush = new System.Drawing.Drawing2D.LinearGradientBrush(new Rectangle(0, 0, image.Width, image.Height), Color.Black, Color.Gray, 1.2f, true);
                g.DrawString(checkCode, font, brush, 2, 1);
                font.Dispose();
                brush.Dispose();

                //画图片的前景噪音点
                for (int i = 0; i < 20; i++)
                {
                    int x = random.Next(image.Width);
                    int y = random.Next(image.Height);

                    image.SetPixel(x, y, Color.FromArgb(0x8b, 0x8b, 0x8b));
                }

                //画图片的边框线
                Pen borderPen = new Pen(Color.Silver);
                g.DrawRectangle(borderPen, 0, 0, image.Width - 1, image.Height - 1);
                borderPen.Dispose();

                System.IO.MemoryStream ms = new System.IO.MemoryStream();
                image.Save(ms, System.Drawing.Imaging.ImageFormat.Bmp);
                byte[] buffer = ms.ToArray();
                ms.Dispose();
                response.ClearContent();
                response.ContentType = "image/bmp";
                response.BinaryWrite(buffer);
            }
            finally
            {
                g.Dispose();
                image.Dispose();
            }
        }
    }
}
