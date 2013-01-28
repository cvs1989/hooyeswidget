using System;
using System.Text;

namespace com.hooyes.lms.LMSMonitor.Model
{
   public class MessageQueue
    {
        public int ID { get; set; }
        public int MID { get; set; }
        public int DayID { get; set; }
        
        /// <summary>
        /// 手机
        /// </summary>
        public string Phone { get; set; }
    }
}
