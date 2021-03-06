﻿//------------------------------------------------------------------------------
// <auto-generated>
//     This code was generated by a tool.
//     Runtime Version:4.0.30319.225
//
//     Changes to this file may cause incorrect behavior and will be lost if
//     the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------

namespace hooyes.API.SinaApiSv {
    
    
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    [System.ServiceModel.ServiceContractAttribute(ConfigurationName="SinaApiSv.ISinaApiService")]
    public interface ISinaApiService {
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/public_timeline", ReplyAction="http://tempuri.org/ISinaApiService/public_timelineResponse")]
        string public_timeline(string userid, string passwd, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/friend_timeline", ReplyAction="http://tempuri.org/ISinaApiService/friend_timelineResponse")]
        string friend_timeline(string userid, string passwd, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/user_timeline", ReplyAction="http://tempuri.org/ISinaApiService/user_timelineResponse")]
        string user_timeline(string userid, string passwd, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/mentions", ReplyAction="http://tempuri.org/ISinaApiService/mentionsResponse")]
        string mentions(string userid, string passwd, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/comments_timeline", ReplyAction="http://tempuri.org/ISinaApiService/comments_timelineResponse")]
        string comments_timeline(string userid, string passwd, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/comments_by_me", ReplyAction="http://tempuri.org/ISinaApiService/comments_by_meResponse")]
        string comments_by_me(string userid, string passwd, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/comments", ReplyAction="http://tempuri.org/ISinaApiService/commentsResponse")]
        string comments(string userid, string passwd, string id, string format);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/counts", ReplyAction="http://tempuri.org/ISinaApiService/countsResponse")]
        string counts(string userid, string passwd, string format, string ids);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/statuses_show", ReplyAction="http://tempuri.org/ISinaApiService/statuses_showResponse")]
        string statuses_show(string userid, string passwd, string format, string id);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/statuses_id", ReplyAction="http://tempuri.org/ISinaApiService/statuses_idResponse")]
        string statuses_id(string userid, string passwd, string id, string uid);
        
        [System.ServiceModel.OperationContractAttribute(Action="http://tempuri.org/ISinaApiService/statuses_update", ReplyAction="http://tempuri.org/ISinaApiService/statuses_updateResponse")]
        string statuses_update(string userid, string passwd, string format, string status);
    }
    
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    public interface ISinaApiServiceChannel : hooyes.API.SinaApiSv.ISinaApiService, System.ServiceModel.IClientChannel {
    }
    
    [System.Diagnostics.DebuggerStepThroughAttribute()]
    [System.CodeDom.Compiler.GeneratedCodeAttribute("System.ServiceModel", "4.0.0.0")]
    public partial class SinaApiServiceClient : System.ServiceModel.ClientBase<hooyes.API.SinaApiSv.ISinaApiService>, hooyes.API.SinaApiSv.ISinaApiService {
        
        public SinaApiServiceClient() {
        }
        
        public SinaApiServiceClient(string endpointConfigurationName) : 
                base(endpointConfigurationName) {
        }
        
        public SinaApiServiceClient(string endpointConfigurationName, string remoteAddress) : 
                base(endpointConfigurationName, remoteAddress) {
        }
        
        public SinaApiServiceClient(string endpointConfigurationName, System.ServiceModel.EndpointAddress remoteAddress) : 
                base(endpointConfigurationName, remoteAddress) {
        }
        
        public SinaApiServiceClient(System.ServiceModel.Channels.Binding binding, System.ServiceModel.EndpointAddress remoteAddress) : 
                base(binding, remoteAddress) {
        }
        
        public string public_timeline(string userid, string passwd, string format) {
            return base.Channel.public_timeline(userid, passwd, format);
        }
        
        public string friend_timeline(string userid, string passwd, string format) {
            return base.Channel.friend_timeline(userid, passwd, format);
        }
        
        public string user_timeline(string userid, string passwd, string format) {
            return base.Channel.user_timeline(userid, passwd, format);
        }
        
        public string mentions(string userid, string passwd, string format) {
            return base.Channel.mentions(userid, passwd, format);
        }
        
        public string comments_timeline(string userid, string passwd, string format) {
            return base.Channel.comments_timeline(userid, passwd, format);
        }
        
        public string comments_by_me(string userid, string passwd, string format) {
            return base.Channel.comments_by_me(userid, passwd, format);
        }
        
        public string comments(string userid, string passwd, string id, string format) {
            return base.Channel.comments(userid, passwd, id, format);
        }
        
        public string counts(string userid, string passwd, string format, string ids) {
            return base.Channel.counts(userid, passwd, format, ids);
        }
        
        public string statuses_show(string userid, string passwd, string format, string id) {
            return base.Channel.statuses_show(userid, passwd, format, id);
        }
        
        public string statuses_id(string userid, string passwd, string id, string uid) {
            return base.Channel.statuses_id(userid, passwd, id, uid);
        }
        
        public string statuses_update(string userid, string passwd, string format, string status) {
            return base.Channel.statuses_update(userid, passwd, format, status);
        }
    }
}
