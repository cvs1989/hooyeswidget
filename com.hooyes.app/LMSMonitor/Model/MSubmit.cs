using System;
namespace com.hooyes.lms.Svc.Model
{
    public class MSubmit
    {
        public int MID { get; set; }
        public string IDCard { get; set; }
        public string IDSN { get; set; }
        public int Year { get; set; }
        public DateTime RegDate { get; set; }

        //Report
        public int Score { get; set; }
        public int Status { get; set; }
        public decimal Compulsory { get; set; }
        public decimal Elective { get; set; }
    }
}
