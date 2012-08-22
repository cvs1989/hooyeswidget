/**
 * Autogenerated by Thrift Compiler (0.8.0)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;
using System.IO;
using Thrift;
using Thrift.Collections;
using Thrift.Protocol;
using Thrift.Transport;

namespace Hdfs.Library
{

  [Serializable]
  public partial class MalformedInputException : Exception, TBase
  {
    private string _msg;

    public string Msg
    {
      get
      {
        return _msg;
      }
      set
      {
        __isset.msg = true;
        this._msg = value;
      }
    }


    public Isset __isset;
    [Serializable]
    public struct Isset {
      public bool msg;
    }

    public MalformedInputException() {
    }

    public void Read (TProtocol iprot)
    {
      TField field;
      iprot.ReadStructBegin();
      while (true)
      {
        field = iprot.ReadFieldBegin();
        if (field.Type == TType.Stop) { 
          break;
        }
        switch (field.ID)
        {
          case -1:
            if (field.Type == TType.String) {
              Msg = iprot.ReadString();
            } else { 
              TProtocolUtil.Skip(iprot, field.Type);
            }
            break;
          default: 
            TProtocolUtil.Skip(iprot, field.Type);
            break;
        }
        iprot.ReadFieldEnd();
      }
      iprot.ReadStructEnd();
    }

    public void Write(TProtocol oprot) {
      TStruct struc = new TStruct("MalformedInputException");
      oprot.WriteStructBegin(struc);
      TField field = new TField();
      if (Msg != null && __isset.msg) {
        field.Name = "msg";
        field.Type = TType.String;
        field.ID = -1;
        oprot.WriteFieldBegin(field);
        oprot.WriteString(Msg);
        oprot.WriteFieldEnd();
      }
      oprot.WriteFieldStop();
      oprot.WriteStructEnd();
    }

    public override string ToString() {
      StringBuilder sb = new StringBuilder("MalformedInputException(");
      sb.Append("Msg: ");
      sb.Append(Msg);
      sb.Append(")");
      return sb.ToString();
    }

  }

}
