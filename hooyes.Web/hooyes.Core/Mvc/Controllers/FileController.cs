using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
using System.IO;

namespace hooyes.Core.Mvc.Controllers
{
    public class FileController:Controller
    {
        public ActionResult Save()
        {
            string FilePath = AppDomain.CurrentDomain.BaseDirectory;
            FilePath = Path.Combine(FilePath, "App_Data");
            string result = "";

            int l = Request.Files.Count;

            var c= Request.Files[0];

            if (c != null)
            {
                string fileName = Path.Combine(FilePath, c.FileName);
                c.SaveAs(fileName);
                result = fileName;
            }
            else
            {
                result = "no file";

            }
            return Content(result);
        }
    }
}
