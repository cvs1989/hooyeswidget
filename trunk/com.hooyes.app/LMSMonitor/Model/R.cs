/*
 关于R 中 Code的数值约定
接口参数错误
 * 用101~199以内的数字表示。
存储过程错误
 * 用201~299以内的数字表示。
程序访问数据库异常
 * 用301~399以内的数字表示。
程序异常
 * 用401~499以内的数字表示。
接口调用成功
 * 用数字0表示。
*/
using System;
using System.Text;

namespace com.hooyes.lms.Svc.Model
{
    public class R
    {
        public int Code { get; set; }
        public int Value { get; set; }
        public decimal SN { get; set; }
        public string Message { get; set; }
    }
}
