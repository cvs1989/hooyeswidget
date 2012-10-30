using System;
using System.Threading;
using com.hooyes.lms.Svc.Model;
using com.hooyes.lms.API;

namespace com.hooyes.lms.Svc.DAL
{
    public class Task
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static R CommitReport(Member m, Report re)
        {
            var r = new R();
            try
            {
                if (re.Status == 0)
                {
                    var para = new AnnalParams();
                    para.certId = m.IDCard;
                    para.orderId = m.IDSN;
                    para.credits = "";
                    para.classHour = "24";
                    para.startTeachDate = m.RegDate.ToString("yyyy-MM-dd");
                    para.endTeachDate = DateTime.Now.ToString("yyyy-MM-dd");
                    para.isPass = "Unpass";

                    if (m.Year == 2012)
                    {
                        if (re.Score >= 60 && (re.Elective + re.Compulsory) * 45 >= 1080)
                        {
                            para.isPass = "Pass";
                        }
                    }
                    else
                    {
                        if ((re.Elective + re.Compulsory) * 45 >= 1080)
                        {
                            para.isPass = "Pass";
                        }
                    }

                    //已完成学习
                    if (para.isPass == "Pass")
                    {
                        var ps = Teach.TeachAnnalAction(para);
                        if (ps.annalValue == "annal000" || ps.annalValue == "annal003")
                        {
                            re.Status = 1;
                            DAL.Update.Report(re);
                            r.Value = 1;
                            r.Message = "success";
                            r.Code = 0;
                        }
                        else
                        {
                            log.Warn("{0},{1},{2}", ps.annalValue, ps.personName, ps.yearValue);
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                log.Fatal("{0},{1}", ex.Message, ex.StackTrace);
            }
            return r;
        }
        public static void Run()
        {
            var SList = Get.MSubmitList();
            foreach (var S in SList)
            {
                Thread.Sleep(1000);
                try
                {
                    var m = new Member();
                    var r = new Report();

                    m.IDCard = S.IDCard;
                    m.IDSN = S.IDSN;
                    m.Year = S.Year;
                    m.RegDate = S.RegDate;
                    m.MID = S.MID;

                    r.MID = S.MID;
                    r.Compulsory = S.Compulsory;
                    r.Elective = S.Elective;
                    r.Status = S.Status;
                    r.Score = S.Score;

                    CommitReport(m, r);

                }
                catch (Exception ex)
                {
                    log.Fatal("{0},{1}", ex.Message, ex.StackTrace);
                }

            }
        }
    }
}
