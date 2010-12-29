using System.Web.Mvc;
using OAuth.Core;
using OAuth.Core.Interfaces;
using OAuth.MVC.Library.Results;
using System.Web.Routing;

namespace OAuth.MVC.Library.Controllers
{
  public class OAuthController:Controller
  {
    private readonly IOAuthContextBuilder _oAuthContextBuilder;
    private readonly IOAuthProvider _oAuthProvider;

    public OAuthController(IOAuthContextBuilder oAuthContextBuilder,IOAuthProvider oAuthProvider)
    {
      
      _oAuthContextBuilder = oAuthContextBuilder;
      _oAuthProvider = oAuthProvider;
    }
    public ActionResult RequestToken(System.Web.HttpRequestBase b)
    {
        var oauthContext = _oAuthContextBuilder.FromHttpRequest(b);
       // var oauthContext = _oAuthContextBuilder.FromHttpRequest(context.HttpContext.Request);
        try
        {
            var token = _oAuthProvider.GrantRequestToken(oauthContext);
            return new OAuthTokenResult(token);
        }
        catch (OAuthException e)
        {
            return new OAuthExceptionResult(e);

        }
    }
    public ActionResult AccessToken(System.Web.HttpRequestBase b)
    {
      var oauthContext = _oAuthContextBuilder.FromHttpRequest(b);
      try
      {
        var token = _oAuthProvider.ExchangeRequestTokenForAccessToken(oauthContext);
        return new OAuthTokenResult(token);
      }
      catch (OAuthException e)
      {
        return new OAuthExceptionResult(e);
      }
    }
    
    

    
  }
}