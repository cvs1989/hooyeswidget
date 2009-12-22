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
            //��ҳ��
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
            //�Ƿ���ʾ������
            if (showTotal == true)
            {
                sb.AppendFormat("<li class='pageCount'>��<b><font color='red'>{0}</font></b>{1}</li>", totalNum, strUnit);
                //strTmp = strTmp + "��<b><font color='red'>" + totalNum + "</font></b>" + strUnit;
            }

            if (currentPage < 2)
            {
                //strTmp = strTmp + "��ҳ&nbsp;&nbsp;��һҳ";
                sb.Append("<li>��ҳ</li>");
                sb.Append("<li>��һҳ</li>");
            }
            else
            {
                //strTmp = strTmp + "<a href='" + fileName + param + "=1'>��ҳ</a>&nbsp;&nbsp;";
                //strTmp = strTmp + "<a href='" + fileName + param + "=" + (currentPage - 1) + "'>��һҳ</a>";
                sb.AppendFormat("<li><a href='{0}=1'>��ҳ</a></li>", fileName + param);
                sb.AppendFormat("<li><a href='{0}={1}'>��һҳ</a>", fileName + param, (currentPage - 1));
            }
            if ((pageTotal - currentPage) < 1)
            {
                //strTmp = strTmp + "��һҳ&nbsp;&nbsp;βҳ";
                sb.Append("<li>��һҳ</li>");
                sb.Append("<li>βҳ</li>");
            }
            else
            {
                //strTmp = strTmp + "&nbsp;&nbsp;<a href='" + fileName + param + "=" + (currentPage + 1) + "'>��һҳ</a>&nbsp;&nbsp;&nbsp;";
                //strTmp = strTmp + "<a href='" + fileName + param + "=" + pageTotal + "'>βҳ</a>";
                sb.AppendFormat("<li><a href='{0}={1}'>��һҳ</a></li>", fileName + param, (currentPage + 1));
                sb.AppendFormat("<li><a href='{0}={1}'>βҳ</a></li>", fileName + param, pageTotal);


            }
            //strTmp = strTmp + "ҳ�Σ�<strong><font color='red'>" + currentPage + "</font>/" + pageTotal + "</strong>ҳ";
            //strTmp = strTmp + "<b>" + pageSize + "</b>" + strUnit + "/ҳ";
            //strTmp = strTmp + "</td></tr></table></form>";
            sb.AppendFormat("<li class='pageIndex'>ҳ�Σ�<strong><font color='red'>{0}</font>/{1}</strong>ҳ</li>", currentPage, pageTotal);
            sb.AppendFormat("<li class='pagePer'><b>{0}</b>{1}/ҳ</li>", pageSize, strUnit);
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
           //��ҳ��
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
           //�Ƿ���ʾ������
           if (showTotal == true)
           {
               sb.AppendFormat("| ��<b>{0}</b>{1} |", totalNum, strUnit);
               //strTmp = strTmp + "��<b><font color='red'>" + totalNum + "</font></b>" + strUnit;
           }

           if (currentPage < 2)
           {
               //strTmp = strTmp + "��ҳ&nbsp;&nbsp;��һҳ";
               sb.Append(" ��ҳ |");
               sb.Append(" ��һҳ |");
           }
           else
           {
               //strTmp = strTmp + "<a href='" + fileName + param + "=1'>��ҳ</a>&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + (currentPage - 1) + "'>��һҳ</a>";
               sb.AppendFormat(" <a href='{0}=1'>��ҳ</a> |", fileName + param);
               sb.AppendFormat(" <a href='{0}={1}'>��һҳ</a> |", fileName + param, (currentPage - 1));
           }
           if ((pageTotal - currentPage) < 1)
           {
               //strTmp = strTmp + "��һҳ&nbsp;&nbsp;βҳ";
               sb.Append(" ��һҳ |");
               sb.Append(" βҳ |");
           }
           else
           {
               //strTmp = strTmp + "&nbsp;&nbsp;<a href='" + fileName + param + "=" + (currentPage + 1) + "'>��һҳ</a>&nbsp;&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + pageTotal + "'>βҳ</a>";
               sb.AppendFormat(" <a href='{0}={1}'>��һҳ</a> |", fileName + param, (currentPage + 1));
               sb.AppendFormat(" <a href='{0}={1}'>βҳ</a> |", fileName + param, pageTotal);


           }
           //strTmp = strTmp + "ҳ�Σ�<strong><font color='red'>" + currentPage + "</font>/" + pageTotal + "</strong>ҳ";
           //strTmp = strTmp + "<b>" + pageSize + "</b>" + strUnit + "/ҳ";
           //strTmp = strTmp + "</td></tr></table></form>";
           sb.AppendFormat(" ҳ�Σ�<strong>{0}/{1}</strong>ҳ |", currentPage, pageTotal);
           sb.AppendFormat(" <b>{0}</b>{1}/ҳ ", pageSize, strUnit);
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
           //��ҳ��
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
           //�Ƿ���ʾ������
           if (showTotal == true)
           {
               sb.AppendFormat("| Total <b>{0}</b> {1} |", totalNum, strUnit);
               //strTmp = strTmp + "��<b><font color='red'>" + totalNum + "</font></b>" + strUnit;
           }

           if (currentPage < 2)
           {
               //strTmp = strTmp + "��ҳ&nbsp;&nbsp;��һҳ";
               sb.Append(" Home |");
               sb.Append(" Previous  |");
           }
           else
           {
               //strTmp = strTmp + "<a href='" + fileName + param + "=1'>��ҳ</a>&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + (currentPage - 1) + "'>��һҳ</a>";
               sb.AppendFormat(" <a href='{0}=1'>Home</a> |", fileName + param);
               sb.AppendFormat(" <a href='{0}={1}'>Previous</a> |", fileName + param, (currentPage - 1));
           }
           if ((pageTotal - currentPage) < 1)
           {
               //strTmp = strTmp + "��һҳ&nbsp;&nbsp;βҳ";
               sb.Append(" Next |");
               sb.Append(" End |");
           }
           else
           {
               //strTmp = strTmp + "&nbsp;&nbsp;<a href='" + fileName + param + "=" + (currentPage + 1) + "'>��һҳ</a>&nbsp;&nbsp;&nbsp;";
               //strTmp = strTmp + "<a href='" + fileName + param + "=" + pageTotal + "'>βҳ</a>";
               sb.AppendFormat(" <a href='{0}={1}'>Next</a> |", fileName + param, (currentPage + 1));
               sb.AppendFormat(" <a href='{0}={1}'>End</a> |", fileName + param, pageTotal);


           }
           //strTmp = strTmp + "ҳ�Σ�<strong><font color='red'>" + currentPage + "</font>/" + pageTotal + "</strong>ҳ";
           //strTmp = strTmp + "<b>" + pageSize + "</b>" + strUnit + "/ҳ";
           //strTmp = strTmp + "</td></tr></table></form>";
           sb.AppendFormat(" Pages��<strong>{0}/{1}</strong> |", currentPage, pageTotal);
           sb.AppendFormat(" <b>{0}</b>{1}/Pages ", pageSize, strUnit);
           //return strTmp;

           sb.Append("</span>");
           return sb.ToString();
       }

    }
}
