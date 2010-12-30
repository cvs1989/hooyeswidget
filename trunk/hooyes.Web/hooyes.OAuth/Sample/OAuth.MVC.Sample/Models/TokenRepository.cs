using System;
using System.Collections.Generic;
using System.Web;
using OAuth.Core;
using NLog;
using OAuth.Core.Storage.Interfaces;
using OAuth.Core.Interfaces;
using OAuth.Core.Storage;

namespace OAuth.MVC.Sample.Models
{
    public class TokenRepository
    {
        private Logger log = LogManager.GetCurrentClassLogger();
        readonly Dictionary<string, AccessToken> _accessTokens = new Dictionary<string, AccessToken>();
        readonly Dictionary<string, RequestToken> _requestTokens = new Dictionary<string, RequestToken>();
        public RequestToken GetRequestToken(string token)
        {
            if (_requestTokens.ContainsKey(token))
                return _requestTokens[token];
            return null;
        }

        public AccessToken GetAccessToken(string token)
        {
            if (_accessTokens.ContainsKey(token))
                return _accessTokens[token];
            return null;
        }

        public void SaveRequestToken(RequestToken token)
        {
            log.Debug<RequestToken>(token);

            _requestTokens[token.Token] = token;

            OCache.save(token);
        }

        public void SaveAccessToken(AccessToken token)
        {
            _accessTokens[token.Token] = token;
        }
    }
    public class RequestToken : TokenBase
    {
        public bool AccessDenied { get; set; }
        public bool UsedUp { get; set; }
        public AccessToken AccessToken { get; set; }
    }
    public class AccessToken : TokenBase
    {
        public string UserName { get; set; }
        public DateTime ExpireyDate { get; set; }
    }


    internal class SampleMemoryTokenStore : ITokenStore
    {

        private Logger log = LogManager.GetCurrentClassLogger();
        readonly TokenRepository _repository;

        public SampleMemoryTokenStore(TokenRepository repository)
        {
            _repository = repository;

        }

        #region ITokenStore Members

        public IToken CreateRequestToken(IOAuthContext context)
        {
            var token = new RequestToken
            {
                ConsumerKey = context.ConsumerKey,
                Realm = context.Realm,
                Token = Guid.NewGuid().ToString(),
                TokenSecret = Guid.NewGuid().ToString(),
                AccessDenied = true,
            };
            log.Debug<IToken>(token);
            _repository.SaveRequestToken(token);

            return token;
        }

        public void ConsumeRequestToken(IOAuthContext requestContext)
        {
            RequestToken requestToken = _repository.GetRequestToken(requestContext.Token);

            if (requestToken.UsedUp)
            {
                throw new OAuthException(requestContext, OAuthProblems.TokenRejected,
                                         "The request token has already be consumed.");
            }
            if (!requestToken.AccessDenied)
                requestToken.UsedUp = true;

            _repository.SaveRequestToken(requestToken);
        }

        public void ConsumeAccessToken(IOAuthContext accessContext)
        {
            AccessToken accessToken = _repository.GetAccessToken(accessContext.Token);

            if (accessToken.ExpireyDate < DateTime.Now)
            {
                throw new OAuthException(accessContext, OAuthProblems.TokenExpired,
                                         "Token has expired (they're only valid for 1 minute)");
            }
        }

        public IToken GetAccessTokenAssociatedWithRequestToken(IOAuthContext requestContext)
        {
            RequestToken request = _repository.GetRequestToken(requestContext.Token);
            return request.AccessToken;
        }

        public RequestForAccessStatus GetStatusOfRequestForAccess(IOAuthContext accessContext)
        {
            RequestToken request = _repository.GetRequestToken(accessContext.Token);

            if (request.AccessDenied) return RequestForAccessStatus.Denied;

            if (request.AccessToken == null) return RequestForAccessStatus.Unknown;

            return RequestForAccessStatus.Granted;
        }

        public IToken GetToken(IOAuthContext context)
        {
            var token = (IToken)null;
            if (!string.IsNullOrEmpty(context.Token))
            {
                token = _repository.GetAccessToken(context.Token) ??
                        (IToken)_repository.GetRequestToken(context.Token);
            }
            return token;
        }

        #endregion
    }
}