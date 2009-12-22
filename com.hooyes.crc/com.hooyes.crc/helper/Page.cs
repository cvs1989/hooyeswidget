using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.crc.helper
{
   public class Page
    {

        public static string ShowPage(string fileName, string param, int totalNum, int pageSize, int currentPage, bool showTotal, string strUnit)
        {
            if (currentPage == 0)
            {
                currentPage = 0;
            }
            if (totalNum == 0)
            {
                totalNum = 0;
            }
            //总页数
            int pageTotal = 0;
            if ((totalNum % pageSize) == 0)
            {
                pageTotal = totalNum / pageSize;
            }
            else
            {
                pageTotal = totalNum / pageSize + 1;
            }
            StringBuilder sb = new StringBuilder();
            sb.Append("<ul class='pageList'>");

            //string strTmp = "<form class='pageForm' style='margin:0px' name='showpages' method='get' action='" + fileName + "'><table class='pageList'><tr><td>";
            //是否显示总条数
            if (showTotal == true)
            {
                sb.AppendFormat("<li class='pageCount'>共<b><font color='red'>{0}</font></b>{1}</li>", totalNum, strUnit);
                //strTmp = strTmp + "共<b><font color='red'>" + totalNum + "</font></b>" + strUnit;
            }

            if (currentPage < 2)
            {
                //strTmp = strTmp + "首页&nbsp;&nbsp;上一页";
                sb.Append("<li>首页</li>");
                sb.Append("<li>上一页</li>");
            }
            else
            {
                //strTmp = strTmp + "<a href='" + fileName + param + "=1'>首页</a>&nbsp;&nbsp;";
                //strTmp = strTmp + "<a href='" + fileName + param + "=" + (currentPage - 1) + "'>上一页</a>";
                sb.AppendFormat("<li><a href='{0}=1'>首页</a></li>", fileName + param);
                sb.AppendFormat("<li><a href='{0}={1}'>上一页</a>", fileName + param, (currentPage - 1));
            }
            if ((pageTotal - currentPage) < 1)
            {
                //strTmp = strTmp + "下一页&nbsp;&nbsp;尾页";
                sb.Append("<li>下一页</li>");
                sb.Append("<li>尾页</li>");
            }
            else
            {
                //strTmp = strTmp + "&nbsp;&nbsp;<a href='" + fileName + param + "=" + (currentPage + 1) + "'>下一页</a>&nbsp;&nbsp;&nbsp;";
                //strTmp = strTmp + "<a href='" + fileName + param + "=" + pageTotal + "'>尾页</a>";
                sb.AppendFormat("<li><a href='{0}={1}'>下一页</a></li>", fileName + param, (currentPage + 1));
                sb.AppendFormat("<li><a href='{0}={1}'>尾页</a></li>", fileName + param, pageTotal);


            }
            //strTmp = strTmp + "页次：<strong><font color='red'>" + currentPage + "</font>/" + pageTotal + "</strong>页";
            //strTmp = strTmp + "<b>" + pageSize + "</b>" + strUnit + "/页";
            //strTmp = strTmp + "</td></tr></table></form>";
            sb.AppendFormat("<li class='pageIndex'>页次：<strong><font color='red'>{0}</font>/{1}</strong>页</li>", currentPage, pageTotal);
            sb.AppendFormat("<li class='pagePer'><b>{0}</b>{1}/页</li>", pageSize, strUnit);
            //return strTmp;

            sb.Append("</ul>");
            return sb.ToString();
        }
       public static string ShowPageCN(string fileName, string param, int totalNum, int pageSize, int currentPage, bool showTotal, string strUnit)
       {
           if (currentPage == 0)
           {
               currentPage = 0;
           }
           if (totalNum == 0)
           {
               totalNum = 0;
           }
           //总页数
           int pageTotal = 0;
           if ((totalNum % pageSize) == 0)
           {
               pageTotal = totalNum / pageSize;
           }
           else
           {
               pageTotal = totalNum / pageSize + 1;
           }
           StringBuilder sb = new StringBuilder();
           sb.Append("<span class='pageListSpan'>");

           //string strTmp = "<form class='pageForm' style='margin:0px' name='showpages' method='get' action='" + fileName + "'><table class='pageList'><tr><td>";
           //是否显示总条数
           if (showTotal == true)
           {
               sb.AppendFormat("| 共<b>{0}</b>{1} |", totalNum, strUnit);
               //strTmp = strTmp + "共<b><font color='red'>" + totalNum + "</font></b>" + strUnit;
           }

           if (currentPage < 2)
           {
               //strTmp = strTmp + "首页&nbsp;&nbsp;上一页";
               sb.Append(" 首页 |");
               sb.Append(" 上一页 |");
           }
           else
           {
               //strTmp = strTmp + "<a href='" + fileName + param + "=1'>首页</a>&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + (currentPage - 1) + "'>上一页</a>";
               sb.AppendFormat(" <a href='{0}=1'>首页</a> |", fileName + param);
               sb.AppendFormat(" <a href='{0}={1}'>上一页</a> |", fileName + param, (currentPage - 1));
           }
           if ((pageTotal - currentPage) < 1)
           {
               //strTmp = strTmp + "下一页&nbsp;&nbsp;尾页";
               sb.Append(" 下一页 |");
               sb.Append(" 尾页 |");
           }
           else
           {
               //strTmp = strTmp + "&nbsp;&nbsp;<a href='" + fileName + param + "=" + (currentPage + 1) + "'>下一页</a>&nbsp;&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + pageTotal + "'>尾页</a>";
               sb.AppendFormat(" <a href='{0}={1}'>下一页</a> |", fileName + param, (currentPage + 1));
               sb.AppendFormat(" <a href='{0}={1}'>尾页</a> |", fileName + param, pageTotal);


           }
           //strTmp = strTmp + "页次：<strong><font color='red'>" + currentPage + "</font>/" + pageTotal + "</strong>页";
           //strTmp = strTmp + "<b>" + pageSize + "</b>" + strUnit + "/页";
           //strTmp = strTmp + "</td></tr></table></form>";
           sb.AppendFormat(" 页次：<strong>{0}/{1}</strong>页 |", currentPage, pageTotal);
           sb.AppendFormat(" <b>{0}</b>{1}/页 ", pageSize, strUnit);
           //return strTmp;

           sb.Append("</span>");
           return sb.ToString();
       }
       public static string ShowPageEN(string fileName, string param, int totalNum, int pageSize, int currentPage, bool showTotal, string strUnit)
       {
           if (currentPage == 0)
           {
               currentPage = 0;
           }
           if (totalNum == 0)
           {
               totalNum = 0;
           }
           //总页数
           int pageTotal = 0;
           if ((totalNum % pageSize) == 0)
           {
               pageTotal = totalNum / pageSize;
           }
           else
           {
               pageTotal = totalNum / pageSize + 1;
           }
           StringBuilder sb = new StringBuilder();
           sb.Append("<span class='pageListSpan'>");

           //string strTmp = "<form class='pageForm' style='margin:0px' name='showpages' method='get' action='" + fileName + "'><table class='pageList'><tr><td>";
           //是否显示总条数
           if (showTotal == true)
           {
               sb.AppendFormat("| Total <b>{0}</b> {1} |", totalNum, strUnit);
               //strTmp = strTmp + "共<b><font color='red'>" + totalNum + "</font></b>" + strUnit;
           }

           if (currentPage < 2)
           {
               //strTmp = strTmp + "首页&nbsp;&nbsp;上一页";
               sb.Append(" Home |");
               sb.Append(" Previous  |");
           }
           else
           {
               //strTmp = strTmp + "<a href='" + fileName + param + "=1'>首页</a>&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + (currentPage - 1) + "'>上一页</a>";
               sb.AppendFormat(" <a href='{0}=1'>Home</a> |", fileName + param);
               sb.AppendFormat(" <a href='{0}={1}'>Previous</a> |", fileName + param, (currentPage - 1));
           }
           if ((pageTotal - currentPage) < 1)
           {
               //strTmp = strTmp + "下一页&nbsp;&nbsp;尾页";
               sb.Append(" Next |");
               sb.Append(" End |");
           }
           else
           {
               //strTmp = strTmp + "&nbsp;&nbsp;<a href='" + fileName + param + "=" + (currentPage + 1) + "'>下一页</a>&nbsp;&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + pageTotal + "'>尾页</a>";
               sb.AppendFormat(" <a href='{0}={1}'>Next</a> |", fileName + param, (currentPage + 1));
               sb.AppendFormat(" <a href='{0}={1}'>End</a> |", fileName + param, pageTotal);


           }
           //strTmp = strTmp + "页次：<strong><font color='red'>" + currentPage + "</font>/" + pageTotal + "</strong>页";
           //strTmp = strTmp + "<b>" + pageSize + "</b>" + strUnit + "/页";
           //strTmp = strTmp + "</td></tr></table></form>";
           sb.AppendFormat(" Pages：<strong>{0}/{1}</strong> |", currentPage, pageTotal);
           sb.AppendFormat(" <b>{0}</b>{1}/Pages ", pageSize, strUnit);
           //return strTmp;

           sb.Append("</span>");
           return sb.ToString();
       }

    }
}
