using System;
using System.Text;

namespace com.hooyes.lms.Svc.Model
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
        /// <summary>
        /// 消息
        /// </summary>
        public string Message { get; set; }
        public DateTime CreateDate { get; set; }
        public DateTime UpdateDate { get; set; }
    }
}
