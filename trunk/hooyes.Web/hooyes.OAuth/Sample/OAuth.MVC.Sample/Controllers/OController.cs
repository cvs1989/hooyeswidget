using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using OAuth.MVC.Library.Controllers;
using OAuth.Core.Interfaces;
using OAuth.Core;
using OAuth.Core.Provider;
using System.Web.Routing;
using OAuth.Core.Signing;
using OAuth.Core.Provider.Inspectors;
using OAuth.Core.Storage.Interfaces;
namespace OAuth.MVC.Sample.Controllers
{
    public class OController : Controller
    {
       private IOAuthContextBuilder oAuthContextBuilder;
       private TokenRepository tr;
       private ITokenStore tokenstore;
       private IConsumerStore iCStore;
       private IContextInspector Inspector;
       private IOAuthProvider oAuthProvider;
       private OAuthController target;
        public OController()
        {
             oAuthContextBuilder = new OAuthContextBuilder();
             tr = new TokenRepository();
             tokenstore = new SampleMemoryTokenStore(tr);

             iCStore = new SampleConsumerStore();

             Inspector = new ConsumerValidationInspector(iCStore);


             oAuthProvider = new OAuthProvider(tokenstore, Inspector);
             target = new OAuthController(oAuthContextBuilder, oAuthProvider); 
        }
        public ActionResult index()
        {
            IOAuthContext authContext = new OAuthContextBuilder().FromHttpRequest(Request);
            //authContext.TokenSecret = "54cae7d23876c298eaaf5cb0d14d0cd9";
            OAuthContextSigner target = new OAuthContextSigner();
            SigningContext sign=new SigningContext();
            sign.ConsumerSecret = "54cae7d23876c298eaaf5cb0d14d0cd9";
           // sign.SignatureBase = "";
           // sign.Algorithm = new System.Security.Cryptography.AsymmetricAlgorithm();
           var b= target.ValidateSignature(authContext, sign);
            return Content(b.ToString());
        }

        public ActionResult RequestToken()
        {
            ActionResult actual;
            actual = target.RequestToken(Request);

            return actual;
        }

        public ActionResult AccessToken()
        {
           
            ActionResult actual;
            actual = target.AccessToken(Request);

            return actual;

        }



    }
}
