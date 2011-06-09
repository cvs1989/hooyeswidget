using System.Net;
using System.Web;

namespace LeoShi.Soft.OpenSinaAPI
{
    public class HttpGet : BaseHttpRequest
    {
        private const string GET = "GET";
        //private const string AccessToken = "http://api.t.sina.com.cn/oauth/access_token";
        //private const string AUTHORIZE = "http://api.t.sina.com.cn/oauth/authorize";
        //private const string RequestToken = "http://api.t.sina.com.cn/oauth/request_token";
        private const string AccessToken = "http://api.weibo.com/oauth/access_token";
        private const string AUTHORIZE = "http://api.weibo.com/oauth/authorize";
        private const string RequestToken = "http://api.weibo.com/oauth/request_token";
        private const string OauthToken = "oauth_token";
        private const string OauthTokenSecret = "oauth_token_secret";
        private const string OuathUserID = "user_id";


        public override string Request(string uri, string postData)
        {
            string outUrl;
            var queryString = AppendSignatureString(GET, uri, out outUrl);
            if (queryString.Length > 0)
            {
                outUrl += "?";
            }
            return WebRequest(GET, outUrl + queryString);
        }

        public void GetAccessToken()
        {
            SetTokenAndTokenSecret(AccessToken);
        }
        public void GetAccessToken(out string user_id)
        {
            SetTokenAndTokenSecret(AccessToken,out user_id);
        }

        private void SetTokenAndTokenSecret(string url)
        {
            var response = Request(url, string.Empty);

            if (response.Length <= 0) return;
            var queryString = HttpUtility.ParseQueryString(response);
            if (queryString[OauthToken] != null)
            {
                Token = queryString[OauthToken];
            }
            if (queryString[OauthTokenSecret] != null)
            {
                TokenSecret = queryString[OauthTokenSecret];
            }
        }
        private void SetTokenAndTokenSecret(string url,out string user_id)
        {
            user_id = null;
            var response = Request(url, string.Empty);

            if (response.Length <= 0) return;
            var queryString = HttpUtility.ParseQueryString(response);
            if (queryString[OauthToken] != null)
            {
                Token = queryString[OauthToken];
            }
            if (queryString[OauthTokenSecret] != null)
            {
                TokenSecret = queryString[OauthTokenSecret];
            }
            user_id = queryString[OuathUserID];
           
        }

        public void GetRequestToken()
        {
            SetTokenAndTokenSecret(RequestToken);
        }

        public string GetAuthorizationUrl()
        {
            var ret = string.Format("{0}?oauth_token={1}", AUTHORIZE, Token);
            return ret;
        }


        private static string WebRequest(string method, string url)
        {
            var httpWebRequest = System.Net.WebRequest.Create(url) as HttpWebRequest;
            httpWebRequest.Method = method;
            httpWebRequest.ServicePoint.Expect100Continue = false;
            return GetHttpWebResponse(httpWebRequest);
        }
    }
}