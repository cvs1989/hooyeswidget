using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.crc.helper
{
    public class Validate
    {
        public static bool GuidString(string guidString)
        {
            bool rValue = true;
            try
            {
                Guid g = new Guid(guidString);
               
            }
            catch
            {
                rValue = false;
            }
            return rValue;
        }
    }
}
