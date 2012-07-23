using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.lms.API
{
    public class ProveParams
    {
        public string schoolId { get; set; }
        public string schoolPas { get; set; }
        public string certId { get; set; }
        public string orderId { get; set; }
    }
    public class AnnalParams : ProveParams
    {
        public string credits { get; set; }
        public string classHour { get; set; }
        /// <summary>
        /// 2012-12-08
        /// </summary>
        public string startTeachDate { get; set; }
        public string endTeachDate { get; set; }
        /// <summary>
        /// Yes/No
        /// </summary>
        public string isPass { get; set; }
    }
}
