using System;
using System.Collections;
using System.Collections.Generic;
using System.Text;
using System.IO;
using Thrift;
using Thrift.Collections;
using Thrift.Protocol;
using Thrift.Transport;


namespace Hbase.Library
{
    [Serializable]
    public partial class BatchMutation : TBase
    {
        private byte[] _row;
        private List<Mutation> _mutations;

        public byte[] Row
        {
            get
            {
                return _row;
            }
            set
            {
                __isset.row = true;
                this._row = value;
            }
        }

        public List<Mutation> Mutations
        {
            get
            {
                return _mutations;
            }
            set
            {
                __isset.mutations = true;
                this._mutations = value;
            }
        }


        public Isset __isset;
        [Serializable]
        public struct Isset
        {
            public bool row;
            public bool mutations;
        }

        public BatchMutation()
        {
        }

        public void Read(TProtocol iprot)
        {
            TField field;
            iprot.ReadStructBegin();
            while (true)
            {
                field = iprot.ReadFieldBegin();
                if (field.Type == TType.Stop)
                {
                    break;
                }
                switch (field.ID)
                {
                    case 1:
                        if (field.Type == TType.String)
                        {
                            Row = iprot.ReadBinary();
                        }
                        else
                        {
                            TProtocolUtil.Skip(iprot, field.Type);
                        }
                        break;
                    case 2:
                        if (field.Type == TType.List)
                        {
                            {
                                Mutations = new List<Mutation>();
                                TList _list0 = iprot.ReadListBegin();
                                for (int _i1 = 0; _i1 < _list0.Count; ++_i1)
                                {
                                    Mutation _elem2 = new Mutation();
                                    _elem2 = new Mutation();
                                    _elem2.Read(iprot);
                                    Mutations.Add(_elem2);
                                }
                                iprot.ReadListEnd();
                            }
                        }
                        else
                        {
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

        public void Write(TProtocol oprot)
        {
            TStruct struc = new TStruct("BatchMutation");
            oprot.WriteStructBegin(struc);
            TField field = new TField();
            if (Row != null && __isset.row)
            {
                field.Name = "row";
                field.Type = TType.String;
                field.ID = 1;
                oprot.WriteFieldBegin(field);
                oprot.WriteBinary(Row);
                oprot.WriteFieldEnd();
            }
            if (Mutations != null && __isset.mutations)
            {
                field.Name = "mutations";
                field.Type = TType.List;
                field.ID = 2;
                oprot.WriteFieldBegin(field);
                {
                    oprot.WriteListBegin(new TList(TType.Struct, Mutations.Count));
                    foreach (Mutation _iter3 in Mutations)
                    {
                        _iter3.Write(oprot);
                    }
                    oprot.WriteListEnd();
                }
                oprot.WriteFieldEnd();
            }
            oprot.WriteFieldStop();
            oprot.WriteStructEnd();
        }

        public override string ToString()
        {
            StringBuilder sb = new StringBuilder("BatchMutation(");
            sb.Append("Row: ");
            sb.Append(Row);
            sb.Append(",Mutations: ");
            sb.Append(Mutations);
            sb.Append(")");
            return sb.ToString();
        }

    }
}