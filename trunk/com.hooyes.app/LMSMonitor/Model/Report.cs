using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.lms.Svc.Model
{
    public class Report
    {
        public int MID { get; set; }
        public int Score { get; set; }
        public decimal Compulsory { get; set; }
        public decimal Elective { get; set; }
        public int Status { get; set; }
        public decimal Minutes { get; set; }
        public string Memo { get; set; }
    }
}
