using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Hbase.Library
{
    public static class Unitl
    {
        public static byte[] StrToBytes(string str)
        {
            byte[] byteArray = System.Text.Encoding.Default.GetBytes(str);

            return byteArray;
        }

        public static string BytesToStr(byte[] byteArray)
        {
            string str = System.Text.Encoding.Default.GetString(byteArray);
            return str;
        }


        public static byte[] ToBytes(this string str)
        {
            byte[] byteArray = System.Text.Encoding.Default.GetBytes(str);

            return byteArray;
        }
    }
}
