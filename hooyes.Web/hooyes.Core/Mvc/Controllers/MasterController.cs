using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
using System.Web.Security;
namespace hooyes.Core.Mvc.Controllers
{
    public class MasterController:Controller
    {
        public ActionResult CreateUser(string UserName,string UserPwd,string email)
        {
            MembershipUser User= Membership.CreateUser(UserName, UserPwd,email);
            if (User != null)
            {
                return Json(User, JsonRequestBehavior.AllowGet);
            }
            else
            {
                return Content("error");
            }
        }
        public ActionResult GetAllUsers()
        {
            MembershipUserCollection MUC = Membership.GetAllUsers();
            return Json(MUC, JsonRequestBehavior.AllowGet);
        }
        public ActionResult LogOn(string userName)
        {
            FormsAuthentication.SetAuthCookie(userName, true);
            return Content("ok");
        }
        public ActionResult SignOut()
        {
            FormsAuthentication.SignOut();
            return Content("ok");
        }
        public ActionResult CreateRoles(string rolesName)
        {
            Roles.CreateRole(rolesName);
            return Content(rolesName);
        }
        public ActionResult AddUserToRole(string userName)
        {
            Roles.AddUserToRole(userName, "users");
            return Content(userName);
        }
        public ActionResult R()
        {
            bool b = Request.IsAuthenticated;
            return Content(b.ToString());
        }
        public ActionResult R2()
        {
            bool b = Roles.IsUserInRole("users");
           
            return Content(b.ToString());
        }
    }
}
