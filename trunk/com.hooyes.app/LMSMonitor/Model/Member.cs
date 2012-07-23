using System;
using System.Text;

namespace com.hooyes.lms.Svc.Model
{
    public class Member : R
    {
        public int ID { get; set; }
        public int MID { get; set; }
        /// <summary>
        /// 姓名
        /// </summary>
        public string Name { get; set; }
        /// <summary>
        /// 身份证号
        /// </summary>
        public string IDCard { get; set; }
        /// <summary>
        /// 报名序号
        /// </summary>
        public string IDSN { get; set; }
        /// <summary>
        /// 学年
        /// </summary>
        public int Year { get; set; }
        /// <summary>
        /// 用户类型 0 行政事业类 1 企业类
        /// </summary>
        public int Type { get; set; }
        /// <summary>
        /// 用户级别
        /// </summary>
        public int Level { get; set; }
        /// <summary>
        /// 手机
        /// </summary>
        public string Phone { get; set; }
        /// <summary>
        /// 注册时间
        /// </summary>
        public DateTime RegDate { get; set; }

    }
    public enum MemberType
    {
        Administration = 0,
        Enterprise = 1
    }
    public enum MemberLevel
    {
        User = 0,
        Administrator = 1
    }
}
