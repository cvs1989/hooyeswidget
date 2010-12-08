using System;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;
using OAuth.Core.Interfaces;
using OAuth.MVC.Library.Binders;
using Rhino.Mocks;
using Xunit;

namespace OAuth.MVC.Tests.Binders
{
  namespace OAuthBinderSpecifications
  {
    public class OAuthBindContext:IUseFixture<MockRepository>
    {
      protected IOAuthContext OAuthContext;
      private readonly Type _defaultModelType = typeof(IConsumer);
      protected object BindResult;
      public void SetFixture(MockRepository mocks)
      {
        var binder = new OAuthBinder();
        var bindingContext = new ModelBindingContext { ModelType = ModelType};
        var mockControllerBase = mocks.DynamicMock<ControllerBase>();
        var mockHttpContext = mocks.DynamicMock<HttpContextBase>();
        var controllerContext = new ControllerContext(mockHttpContext,new RouteData(),mockControllerBase);
        var oauthContextBuilder = mocks.DynamicMock<IOAuthContextBuilder>();
        var mockHttpRequest = mocks.DynamicMock<HttpRequestBase>();
        OAuthContext = mocks.DynamicMock<IOAuthContext>();
        oauthContextBuilder.Stub(contextBuilder => contextBuilder.FromHttpRequest(mockHttpRequest)).Return(OAuthContext);
      
        mockHttpContext.Stub(context => context.Request).Return(mockHttpRequest);
        binder.OAuthContextBuilder = oauthContextBuilder;
        mocks.ReplayAll();
        
       BindResult= binder.BindModel(controllerContext, bindingContext);

      }

      protected virtual Type ModelType
      {
        get { return _defaultModelType; }
      }
    }
// ReSharper disable InconsistentNaming
    public class when_asked_to_bind_to_an_IConsumer:OAuthBindContext
    {
      [Fact]
      public void oauth_context_should_be_returned()
      {
        Assert.Equal(OAuthContext, BindResult);
      }
    }
    public class when_asked_to_bind_to_an_IOAuthRequest : OAuthBindContext
    {
          
      protected override Type ModelType
      {
        get
        {
          return typeof (IOAuthContext);
        }
      }
      [Fact]
      public void oauth_context_should_be_returned()
      {
        Assert.Equal(OAuthContext, BindResult);
      }
    }
    public class when_asked_to_bind_to_an_IToken : OAuthBindContext
    {
      protected override Type ModelType
      {
        get
        {
          return typeof (IToken);
        }
      }
      [Fact]
      public void oauth_context_should_be_returned()
      {
        Assert.Equal(OAuthContext, BindResult);
      }
    }
    public class when_asked_to_bind_to_an_unknown_type : OAuthBindContext
    {
      protected override Type ModelType
      {
        get
        {
          return typeof(IController);
        }
      }
      [Fact]
      public void null_should_be_returned()
      {
        Assert.Null(BindResult);
      }
    }
   }
  // ReSharper restore InconsistentNaming
}