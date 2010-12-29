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

            IOAuthContextBuilder oAuthContextBuilder = new OAuthContextBuilder();
            TokenRepository tr= new TokenRepository();
            Core.Storage.Interfaces.ITokenStore tokenstore=new SampleMemoryTokenStore(tr);

            IConsumerStore iCStore=new SampleConsumerStore();

            IContextInspector Inspector=new ConsumerValidationInspector(iCStore);


            IOAuthProvider oAuthProvider = new OAuthProvider(tokenstore, Inspector);
            OAuthController target = new OAuthController(oAuthContextBuilder, oAuthProvider); // TODO: Initialize to an appropriate value
            //ActionResult expected = null; // TODO: Initialize to an appropriate value
            ActionResult actual;
            actual = target.RequestToken(Request);

            return actual;
        }

        public ActionResult AccessToken()
        {
            IOAuthContextBuilder oAuthContextBuilder = new OAuthContextBuilder();
            TokenRepository tr = new TokenRepository();
            Core.Storage.Interfaces.ITokenStore tokenstore = new SampleMemoryTokenStore(tr);

            IConsumerStore iCStore = new SampleConsumerStore();

            IContextInspector Inspector = new ConsumerValidationInspector(iCStore);


            IOAuthProvider oAuthProvider = new OAuthProvider(tokenstore, Inspector);
            OAuthController target = new OAuthController(oAuthContextBuilder, oAuthProvider); // TODO: Initialize to an appropriate value
            //ActionResult expected = null; // TODO: Initialize to an appropriate value
            ActionResult actual;
            actual = target.AccessToken(Request);

            return actual;

        }



    }
}
