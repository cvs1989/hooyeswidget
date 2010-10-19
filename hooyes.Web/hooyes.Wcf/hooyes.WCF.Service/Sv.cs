using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace hooyes.WCF.Service
{
    public class Sv:ISv
    {
        public string GetData(int value)
        {
            return string.Format("you input {0}", value);
        }

        public void UpLoad(byte[] file)
        {
            string FileName="E:\\wcf.txt";
            File.WriteAllBytes(FileName, file);
        }
    }
}
