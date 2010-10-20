using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.ServiceModel;
using System.Text;

namespace hooyes.WCF.Service
{
    // 注意: 如果更改此处的接口名称 "ISinaApiService"，也必须更新 App.config 中对 "ISinaApiService" 的引用。
    [ServiceContract]
    public interface ISinaApiService
    {
        /**********************************************************************************************
        ********************************获取下行数据集接口*********************************************
       
        **********************************************************************************************/
        /*最新公共微博*/
        [OperationContract]
        string public_timeline(string userid, string passwd, string format);

        /*最新关注人微博*/
        [OperationContract]
        string friend_timeline(string userid, string passwd, string format);

        /*用户发表微薄列表*/
        [OperationContract]
        string user_timeline(string userid, string passwd, string format);
        /*最新n条@我的微博*/
        [OperationContract]
        string mentions(string userid, string passwd, string format);
        /*最新评论*/
        [OperationContract]
        string comments_timeline(string userid, string passwd, string format);
        /*发出的评论*/
        [OperationContract]
        string comments_by_me(string userid, string passwd, string format);
        /* 单条评论列表*/
        [OperationContract]
        string comments(string userid, string passwd, string id, string format);
        /*批量获取一组微博的评论数及转发数*/
        [OperationContract]
        string counts(string userid, string passwd, string format, string ids);
        /**********************************************************************************************
         *************************************微博访问接口*********************************************
         **********************************************************************************************
         **********************************************************************************************/
        /*获取单条ID的微博信息*/
        [OperationContract]
        string statuses_show(string userid, string passwd, string format, string id);
        /*获取单条ID的微博信息*/
        [OperationContract]
        string statuses_id(string userid, string passwd, string id, string uid);
        /*发布一条微博信息*/
        [OperationContract]
        string statuses_update(string userid, string passwd, string format, string status);

    }
}
