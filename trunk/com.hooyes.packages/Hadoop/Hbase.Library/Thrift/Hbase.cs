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
    public class Hbase
    {
        public interface Iface
        {
            void enableTable(byte[] tableName);
            void disableTable(byte[] tableName);
            bool isTableEnabled(byte[] tableName);
            void compact(byte[] tableNameOrRegionName);
            void majorCompact(byte[] tableNameOrRegionName);
            List<byte[]> getTableNames();
            Dictionary<byte[], ColumnDescriptor> getColumnDescriptors(byte[] tableName);
            List<TRegionInfo> getTableRegions(byte[] tableName);
            void createTable(byte[] tableName, List<ColumnDescriptor> columnFamilies);
            void deleteTable(byte[] tableName);
            List<TCell> get(byte[] tableName, byte[] row, byte[] column);
            List<TCell> getVer(byte[] tableName, byte[] row, byte[] column, int numVersions);
            List<TCell> getVerTs(byte[] tableName, byte[] row, byte[] column, long timestamp, int numVersions);
            List<TRowResult> getRow(byte[] tableName, byte[] row);
            List<TRowResult> getRowWithColumns(byte[] tableName, byte[] row, List<byte[]> columns);
            List<TRowResult> getRowTs(byte[] tableName, byte[] row, long timestamp);
            List<TRowResult> getRowWithColumnsTs(byte[] tableName, byte[] row, List<byte[]> columns, long timestamp);
            List<TRowResult> getRows(byte[] tableName, List<byte[]> rows);
            List<TRowResult> getRowsWithColumns(byte[] tableName, List<byte[]> rows, List<byte[]> columns);
            List<TRowResult> getRowsTs(byte[] tableName, List<byte[]> rows, long timestamp);
            List<TRowResult> getRowsWithColumnsTs(byte[] tableName, List<byte[]> rows, List<byte[]> columns, long timestamp);
            void mutateRow(byte[] tableName, byte[] row, List<Mutation> mutations);
            void mutateRowTs(byte[] tableName, byte[] row, List<Mutation> mutations, long timestamp);
            void mutateRows(byte[] tableName, List<BatchMutation> rowBatches);
            void mutateRowsTs(byte[] tableName, List<BatchMutation> rowBatches, long timestamp);
            long atomicIncrement(byte[] tableName, byte[] row, byte[] column, long value);
            void deleteAll(byte[] tableName, byte[] row, byte[] column);
            void deleteAllTs(byte[] tableName, byte[] row, byte[] column, long timestamp);
            void deleteAllRow(byte[] tableName, byte[] row);
            void deleteAllRowTs(byte[] tableName, byte[] row, long timestamp);
            int scannerOpenWithScan(byte[] tableName, TScan scan);
            int scannerOpen(byte[] tableName, byte[] startRow, List<byte[]> columns);
            int scannerOpenWithStop(byte[] tableName, byte[] startRow, byte[] stopRow, List<byte[]> columns);
            int scannerOpenWithPrefix(byte[] tableName, byte[] startAndPrefix, List<byte[]> columns);
            int scannerOpenTs(byte[] tableName, byte[] startRow, List<byte[]> columns, long timestamp);
            int scannerOpenWithStopTs(byte[] tableName, byte[] startRow, byte[] stopRow, List<byte[]> columns, long timestamp);
            List<TRowResult> scannerGet(int id);
            List<TRowResult> scannerGetList(int id, int nbRows);
            void scannerClose(int id);
        }

        public class Client : Iface
        {
            public Client(TProtocol prot)
                : this(prot, prot)
            {
            }

            public Client(TProtocol iprot, TProtocol oprot)
            {
                iprot_ = iprot;
                oprot_ = oprot;
            }

            protected TProtocol iprot_;
            protected TProtocol oprot_;
            protected int seqid_;

            public TProtocol InputProtocol
            {
                get { return iprot_; }
            }
            public TProtocol OutputProtocol
            {
                get { return oprot_; }
            }


            public void enableTable(byte[] tableName)
            {
                send_enableTable(tableName);
                recv_enableTable();
            }

            public void send_enableTable(byte[] tableName)
            {
                oprot_.WriteMessageBegin(new TMessage("enableTable", TMessageType.Call, seqid_));
                enableTable_args args = new enableTable_args();
                args.TableName = tableName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_enableTable()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                enableTable_result result = new enableTable_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public void disableTable(byte[] tableName)
            {
                send_disableTable(tableName);
                recv_disableTable();
            }

            public void send_disableTable(byte[] tableName)
            {
                oprot_.WriteMessageBegin(new TMessage("disableTable", TMessageType.Call, seqid_));
                disableTable_args args = new disableTable_args();
                args.TableName = tableName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_disableTable()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                disableTable_result result = new disableTable_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public bool isTableEnabled(byte[] tableName)
            {
                send_isTableEnabled(tableName);
                return recv_isTableEnabled();
            }

            public void send_isTableEnabled(byte[] tableName)
            {
                oprot_.WriteMessageBegin(new TMessage("isTableEnabled", TMessageType.Call, seqid_));
                isTableEnabled_args args = new isTableEnabled_args();
                args.TableName = tableName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public bool recv_isTableEnabled()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                isTableEnabled_result result = new isTableEnabled_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "isTableEnabled failed: unknown result");
            }

            public void compact(byte[] tableNameOrRegionName)
            {
                send_compact(tableNameOrRegionName);
                recv_compact();
            }

            public void send_compact(byte[] tableNameOrRegionName)
            {
                oprot_.WriteMessageBegin(new TMessage("compact", TMessageType.Call, seqid_));
                compact_args args = new compact_args();
                args.TableNameOrRegionName = tableNameOrRegionName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_compact()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                compact_result result = new compact_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public void majorCompact(byte[] tableNameOrRegionName)
            {
                send_majorCompact(tableNameOrRegionName);
                recv_majorCompact();
            }

            public void send_majorCompact(byte[] tableNameOrRegionName)
            {
                oprot_.WriteMessageBegin(new TMessage("majorCompact", TMessageType.Call, seqid_));
                majorCompact_args args = new majorCompact_args();
                args.TableNameOrRegionName = tableNameOrRegionName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_majorCompact()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                majorCompact_result result = new majorCompact_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public List<byte[]> getTableNames()
            {
                send_getTableNames();
                return recv_getTableNames();
            }

            public void send_getTableNames()
            {
                oprot_.WriteMessageBegin(new TMessage("getTableNames", TMessageType.Call, seqid_));
                getTableNames_args args = new getTableNames_args();
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<byte[]> recv_getTableNames()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getTableNames_result result = new getTableNames_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getTableNames failed: unknown result");
            }

            public Dictionary<byte[], ColumnDescriptor> getColumnDescriptors(byte[] tableName)
            {
                send_getColumnDescriptors(tableName);
                return recv_getColumnDescriptors();
            }

            public void send_getColumnDescriptors(byte[] tableName)
            {
                oprot_.WriteMessageBegin(new TMessage("getColumnDescriptors", TMessageType.Call, seqid_));
                getColumnDescriptors_args args = new getColumnDescriptors_args();
                args.TableName = tableName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public Dictionary<byte[], ColumnDescriptor> recv_getColumnDescriptors()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getColumnDescriptors_result result = new getColumnDescriptors_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getColumnDescriptors failed: unknown result");
            }

            public List<TRegionInfo> getTableRegions(byte[] tableName)
            {
                send_getTableRegions(tableName);
                return recv_getTableRegions();
            }

            public void send_getTableRegions(byte[] tableName)
            {
                oprot_.WriteMessageBegin(new TMessage("getTableRegions", TMessageType.Call, seqid_));
                getTableRegions_args args = new getTableRegions_args();
                args.TableName = tableName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRegionInfo> recv_getTableRegions()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getTableRegions_result result = new getTableRegions_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getTableRegions failed: unknown result");
            }

            public void createTable(byte[] tableName, List<ColumnDescriptor> columnFamilies)
            {
                send_createTable(tableName, columnFamilies);
                recv_createTable();
            }

            public void send_createTable(byte[] tableName, List<ColumnDescriptor> columnFamilies)
            {
                oprot_.WriteMessageBegin(new TMessage("createTable", TMessageType.Call, seqid_));
                createTable_args args = new createTable_args();
                args.TableName = tableName;
                args.ColumnFamilies = columnFamilies;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_createTable()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                createTable_result result = new createTable_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                if (result.__isset.exist)
                {
                    throw result.Exist;
                }
                return;
            }

            public void deleteTable(byte[] tableName)
            {
                send_deleteTable(tableName);
                recv_deleteTable();
            }

            public void send_deleteTable(byte[] tableName)
            {
                oprot_.WriteMessageBegin(new TMessage("deleteTable", TMessageType.Call, seqid_));
                deleteTable_args args = new deleteTable_args();
                args.TableName = tableName;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_deleteTable()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                deleteTable_result result = new deleteTable_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public List<TCell> get(byte[] tableName, byte[] row, byte[] column)
            {
                send_get(tableName, row, column);
                return recv_get();
            }

            public void send_get(byte[] tableName, byte[] row, byte[] column)
            {
                oprot_.WriteMessageBegin(new TMessage("get", TMessageType.Call, seqid_));
                get_args args = new get_args();
                args.TableName = tableName;
                args.Row = row;
                args.Column = column;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TCell> recv_get()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                get_result result = new get_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "get failed: unknown result");
            }

            public List<TCell> getVer(byte[] tableName, byte[] row, byte[] column, int numVersions)
            {
                send_getVer(tableName, row, column, numVersions);
                return recv_getVer();
            }

            public void send_getVer(byte[] tableName, byte[] row, byte[] column, int numVersions)
            {
                oprot_.WriteMessageBegin(new TMessage("getVer", TMessageType.Call, seqid_));
                getVer_args args = new getVer_args();
                args.TableName = tableName;
                args.Row = row;
                args.Column = column;
                args.NumVersions = numVersions;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TCell> recv_getVer()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getVer_result result = new getVer_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getVer failed: unknown result");
            }

            public List<TCell> getVerTs(byte[] tableName, byte[] row, byte[] column, long timestamp, int numVersions)
            {
                send_getVerTs(tableName, row, column, timestamp, numVersions);
                return recv_getVerTs();
            }

            public void send_getVerTs(byte[] tableName, byte[] row, byte[] column, long timestamp, int numVersions)
            {
                oprot_.WriteMessageBegin(new TMessage("getVerTs", TMessageType.Call, seqid_));
                getVerTs_args args = new getVerTs_args();
                args.TableName = tableName;
                args.Row = row;
                args.Column = column;
                args.Timestamp = timestamp;
                args.NumVersions = numVersions;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TCell> recv_getVerTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getVerTs_result result = new getVerTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getVerTs failed: unknown result");
            }

            public List<TRowResult> getRow(byte[] tableName, byte[] row)
            {
                send_getRow(tableName, row);
                return recv_getRow();
            }

            public void send_getRow(byte[] tableName, byte[] row)
            {
                oprot_.WriteMessageBegin(new TMessage("getRow", TMessageType.Call, seqid_));
                getRow_args args = new getRow_args();
                args.TableName = tableName;
                args.Row = row;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRow()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRow_result result = new getRow_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRow failed: unknown result");
            }

            public List<TRowResult> getRowWithColumns(byte[] tableName, byte[] row, List<byte[]> columns)
            {
                send_getRowWithColumns(tableName, row, columns);
                return recv_getRowWithColumns();
            }

            public void send_getRowWithColumns(byte[] tableName, byte[] row, List<byte[]> columns)
            {
                oprot_.WriteMessageBegin(new TMessage("getRowWithColumns", TMessageType.Call, seqid_));
                getRowWithColumns_args args = new getRowWithColumns_args();
                args.TableName = tableName;
                args.Row = row;
                args.Columns = columns;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRowWithColumns()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRowWithColumns_result result = new getRowWithColumns_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRowWithColumns failed: unknown result");
            }

            public List<TRowResult> getRowTs(byte[] tableName, byte[] row, long timestamp)
            {
                send_getRowTs(tableName, row, timestamp);
                return recv_getRowTs();
            }

            public void send_getRowTs(byte[] tableName, byte[] row, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("getRowTs", TMessageType.Call, seqid_));
                getRowTs_args args = new getRowTs_args();
                args.TableName = tableName;
                args.Row = row;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRowTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRowTs_result result = new getRowTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRowTs failed: unknown result");
            }

            public List<TRowResult> getRowWithColumnsTs(byte[] tableName, byte[] row, List<byte[]> columns, long timestamp)
            {
                send_getRowWithColumnsTs(tableName, row, columns, timestamp);
                return recv_getRowWithColumnsTs();
            }

            public void send_getRowWithColumnsTs(byte[] tableName, byte[] row, List<byte[]> columns, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("getRowWithColumnsTs", TMessageType.Call, seqid_));
                getRowWithColumnsTs_args args = new getRowWithColumnsTs_args();
                args.TableName = tableName;
                args.Row = row;
                args.Columns = columns;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRowWithColumnsTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRowWithColumnsTs_result result = new getRowWithColumnsTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRowWithColumnsTs failed: unknown result");
            }

            public List<TRowResult> getRows(byte[] tableName, List<byte[]> rows)
            {
                send_getRows(tableName, rows);
                return recv_getRows();
            }

            public void send_getRows(byte[] tableName, List<byte[]> rows)
            {
                oprot_.WriteMessageBegin(new TMessage("getRows", TMessageType.Call, seqid_));
                getRows_args args = new getRows_args();
                args.TableName = tableName;
                args.Rows = rows;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRows()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRows_result result = new getRows_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRows failed: unknown result");
            }

            public List<TRowResult> getRowsWithColumns(byte[] tableName, List<byte[]> rows, List<byte[]> columns)
            {
                send_getRowsWithColumns(tableName, rows, columns);
                return recv_getRowsWithColumns();
            }

            public void send_getRowsWithColumns(byte[] tableName, List<byte[]> rows, List<byte[]> columns)
            {
                oprot_.WriteMessageBegin(new TMessage("getRowsWithColumns", TMessageType.Call, seqid_));
                getRowsWithColumns_args args = new getRowsWithColumns_args();
                args.TableName = tableName;
                args.Rows = rows;
                args.Columns = columns;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRowsWithColumns()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRowsWithColumns_result result = new getRowsWithColumns_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRowsWithColumns failed: unknown result");
            }

            public List<TRowResult> getRowsTs(byte[] tableName, List<byte[]> rows, long timestamp)
            {
                send_getRowsTs(tableName, rows, timestamp);
                return recv_getRowsTs();
            }

            public void send_getRowsTs(byte[] tableName, List<byte[]> rows, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("getRowsTs", TMessageType.Call, seqid_));
                getRowsTs_args args = new getRowsTs_args();
                args.TableName = tableName;
                args.Rows = rows;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRowsTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRowsTs_result result = new getRowsTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRowsTs failed: unknown result");
            }

            public List<TRowResult> getRowsWithColumnsTs(byte[] tableName, List<byte[]> rows, List<byte[]> columns, long timestamp)
            {
                send_getRowsWithColumnsTs(tableName, rows, columns, timestamp);
                return recv_getRowsWithColumnsTs();
            }

            public void send_getRowsWithColumnsTs(byte[] tableName, List<byte[]> rows, List<byte[]> columns, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("getRowsWithColumnsTs", TMessageType.Call, seqid_));
                getRowsWithColumnsTs_args args = new getRowsWithColumnsTs_args();
                args.TableName = tableName;
                args.Rows = rows;
                args.Columns = columns;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_getRowsWithColumnsTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                getRowsWithColumnsTs_result result = new getRowsWithColumnsTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "getRowsWithColumnsTs failed: unknown result");
            }

            public void mutateRow(byte[] tableName, byte[] row, List<Mutation> mutations)
            {
                send_mutateRow(tableName, row, mutations);
                recv_mutateRow();
            }

            public void send_mutateRow(byte[] tableName, byte[] row, List<Mutation> mutations)
            {
                oprot_.WriteMessageBegin(new TMessage("mutateRow", TMessageType.Call, seqid_));
                mutateRow_args args = new mutateRow_args();
                args.TableName = tableName;
                args.Row = row;
                args.Mutations = mutations;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_mutateRow()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                mutateRow_result result = new mutateRow_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                return;
            }

            public void mutateRowTs(byte[] tableName, byte[] row, List<Mutation> mutations, long timestamp)
            {
                send_mutateRowTs(tableName, row, mutations, timestamp);
                recv_mutateRowTs();
            }

            public void send_mutateRowTs(byte[] tableName, byte[] row, List<Mutation> mutations, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("mutateRowTs", TMessageType.Call, seqid_));
                mutateRowTs_args args = new mutateRowTs_args();
                args.TableName = tableName;
                args.Row = row;
                args.Mutations = mutations;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_mutateRowTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                mutateRowTs_result result = new mutateRowTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                return;
            }

            public void mutateRows(byte[] tableName, List<BatchMutation> rowBatches)
            {
                send_mutateRows(tableName, rowBatches);
                recv_mutateRows();
            }

            public void send_mutateRows(byte[] tableName, List<BatchMutation> rowBatches)
            {
                oprot_.WriteMessageBegin(new TMessage("mutateRows", TMessageType.Call, seqid_));
                mutateRows_args args = new mutateRows_args();
                args.TableName = tableName;
                args.RowBatches = rowBatches;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_mutateRows()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                mutateRows_result result = new mutateRows_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                return;
            }

            public void mutateRowsTs(byte[] tableName, List<BatchMutation> rowBatches, long timestamp)
            {
                send_mutateRowsTs(tableName, rowBatches, timestamp);
                recv_mutateRowsTs();
            }

            public void send_mutateRowsTs(byte[] tableName, List<BatchMutation> rowBatches, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("mutateRowsTs", TMessageType.Call, seqid_));
                mutateRowsTs_args args = new mutateRowsTs_args();
                args.TableName = tableName;
                args.RowBatches = rowBatches;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_mutateRowsTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                mutateRowsTs_result result = new mutateRowsTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                return;
            }

            public long atomicIncrement(byte[] tableName, byte[] row, byte[] column, long value)
            {
                send_atomicIncrement(tableName, row, column, value);
                return recv_atomicIncrement();
            }

            public void send_atomicIncrement(byte[] tableName, byte[] row, byte[] column, long value)
            {
                oprot_.WriteMessageBegin(new TMessage("atomicIncrement", TMessageType.Call, seqid_));
                atomicIncrement_args args = new atomicIncrement_args();
                args.TableName = tableName;
                args.Row = row;
                args.Column = column;
                args.Value = value;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public long recv_atomicIncrement()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                atomicIncrement_result result = new atomicIncrement_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "atomicIncrement failed: unknown result");
            }

            public void deleteAll(byte[] tableName, byte[] row, byte[] column)
            {
                send_deleteAll(tableName, row, column);
                recv_deleteAll();
            }

            public void send_deleteAll(byte[] tableName, byte[] row, byte[] column)
            {
                oprot_.WriteMessageBegin(new TMessage("deleteAll", TMessageType.Call, seqid_));
                deleteAll_args args = new deleteAll_args();
                args.TableName = tableName;
                args.Row = row;
                args.Column = column;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_deleteAll()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                deleteAll_result result = new deleteAll_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public void deleteAllTs(byte[] tableName, byte[] row, byte[] column, long timestamp)
            {
                send_deleteAllTs(tableName, row, column, timestamp);
                recv_deleteAllTs();
            }

            public void send_deleteAllTs(byte[] tableName, byte[] row, byte[] column, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("deleteAllTs", TMessageType.Call, seqid_));
                deleteAllTs_args args = new deleteAllTs_args();
                args.TableName = tableName;
                args.Row = row;
                args.Column = column;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_deleteAllTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                deleteAllTs_result result = new deleteAllTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public void deleteAllRow(byte[] tableName, byte[] row)
            {
                send_deleteAllRow(tableName, row);
                recv_deleteAllRow();
            }

            public void send_deleteAllRow(byte[] tableName, byte[] row)
            {
                oprot_.WriteMessageBegin(new TMessage("deleteAllRow", TMessageType.Call, seqid_));
                deleteAllRow_args args = new deleteAllRow_args();
                args.TableName = tableName;
                args.Row = row;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_deleteAllRow()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                deleteAllRow_result result = new deleteAllRow_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public void deleteAllRowTs(byte[] tableName, byte[] row, long timestamp)
            {
                send_deleteAllRowTs(tableName, row, timestamp);
                recv_deleteAllRowTs();
            }

            public void send_deleteAllRowTs(byte[] tableName, byte[] row, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("deleteAllRowTs", TMessageType.Call, seqid_));
                deleteAllRowTs_args args = new deleteAllRowTs_args();
                args.TableName = tableName;
                args.Row = row;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_deleteAllRowTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                deleteAllRowTs_result result = new deleteAllRowTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                return;
            }

            public int scannerOpenWithScan(byte[] tableName, TScan scan)
            {
                send_scannerOpenWithScan(tableName, scan);
                return recv_scannerOpenWithScan();
            }

            public void send_scannerOpenWithScan(byte[] tableName, TScan scan)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerOpenWithScan", TMessageType.Call, seqid_));
                scannerOpenWithScan_args args = new scannerOpenWithScan_args();
                args.TableName = tableName;
                args.Scan = scan;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public int recv_scannerOpenWithScan()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerOpenWithScan_result result = new scannerOpenWithScan_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerOpenWithScan failed: unknown result");
            }

            public int scannerOpen(byte[] tableName, byte[] startRow, List<byte[]> columns)
            {
                send_scannerOpen(tableName, startRow, columns);
                return recv_scannerOpen();
            }

            public void send_scannerOpen(byte[] tableName, byte[] startRow, List<byte[]> columns)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerOpen", TMessageType.Call, seqid_));
                scannerOpen_args args = new scannerOpen_args();
                args.TableName = tableName;
                args.StartRow = startRow;
                args.Columns = columns;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public int recv_scannerOpen()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerOpen_result result = new scannerOpen_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerOpen failed: unknown result");
            }

            public int scannerOpenWithStop(byte[] tableName, byte[] startRow, byte[] stopRow, List<byte[]> columns)
            {
                send_scannerOpenWithStop(tableName, startRow, stopRow, columns);
                return recv_scannerOpenWithStop();
            }

            public void send_scannerOpenWithStop(byte[] tableName, byte[] startRow, byte[] stopRow, List<byte[]> columns)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerOpenWithStop", TMessageType.Call, seqid_));
                scannerOpenWithStop_args args = new scannerOpenWithStop_args();
                args.TableName = tableName;
                args.StartRow = startRow;
                args.StopRow = stopRow;
                args.Columns = columns;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public int recv_scannerOpenWithStop()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerOpenWithStop_result result = new scannerOpenWithStop_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerOpenWithStop failed: unknown result");
            }

            public int scannerOpenWithPrefix(byte[] tableName, byte[] startAndPrefix, List<byte[]> columns)
            {
                send_scannerOpenWithPrefix(tableName, startAndPrefix, columns);
                return recv_scannerOpenWithPrefix();
            }

            public void send_scannerOpenWithPrefix(byte[] tableName, byte[] startAndPrefix, List<byte[]> columns)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerOpenWithPrefix", TMessageType.Call, seqid_));
                scannerOpenWithPrefix_args args = new scannerOpenWithPrefix_args();
                args.TableName = tableName;
                args.StartAndPrefix = startAndPrefix;
                args.Columns = columns;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public int recv_scannerOpenWithPrefix()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerOpenWithPrefix_result result = new scannerOpenWithPrefix_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerOpenWithPrefix failed: unknown result");
            }

            public int scannerOpenTs(byte[] tableName, byte[] startRow, List<byte[]> columns, long timestamp)
            {
                send_scannerOpenTs(tableName, startRow, columns, timestamp);
                return recv_scannerOpenTs();
            }

            public void send_scannerOpenTs(byte[] tableName, byte[] startRow, List<byte[]> columns, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerOpenTs", TMessageType.Call, seqid_));
                scannerOpenTs_args args = new scannerOpenTs_args();
                args.TableName = tableName;
                args.StartRow = startRow;
                args.Columns = columns;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public int recv_scannerOpenTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerOpenTs_result result = new scannerOpenTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerOpenTs failed: unknown result");
            }

            public int scannerOpenWithStopTs(byte[] tableName, byte[] startRow, byte[] stopRow, List<byte[]> columns, long timestamp)
            {
                send_scannerOpenWithStopTs(tableName, startRow, stopRow, columns, timestamp);
                return recv_scannerOpenWithStopTs();
            }

            public void send_scannerOpenWithStopTs(byte[] tableName, byte[] startRow, byte[] stopRow, List<byte[]> columns, long timestamp)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerOpenWithStopTs", TMessageType.Call, seqid_));
                scannerOpenWithStopTs_args args = new scannerOpenWithStopTs_args();
                args.TableName = tableName;
                args.StartRow = startRow;
                args.StopRow = stopRow;
                args.Columns = columns;
                args.Timestamp = timestamp;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public int recv_scannerOpenWithStopTs()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerOpenWithStopTs_result result = new scannerOpenWithStopTs_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerOpenWithStopTs failed: unknown result");
            }

            public List<TRowResult> scannerGet(int id)
            {
                send_scannerGet(id);
                return recv_scannerGet();
            }

            public void send_scannerGet(int id)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerGet", TMessageType.Call, seqid_));
                scannerGet_args args = new scannerGet_args();
                args.Id = id;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_scannerGet()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerGet_result result = new scannerGet_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerGet failed: unknown result");
            }

            public List<TRowResult> scannerGetList(int id, int nbRows)
            {
                send_scannerGetList(id, nbRows);
                return recv_scannerGetList();
            }

            public void send_scannerGetList(int id, int nbRows)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerGetList", TMessageType.Call, seqid_));
                scannerGetList_args args = new scannerGetList_args();
                args.Id = id;
                args.NbRows = nbRows;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public List<TRowResult> recv_scannerGetList()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerGetList_result result = new scannerGetList_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.success)
                {
                    return result.Success;
                }
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                throw new TApplicationException(TApplicationException.ExceptionType.MissingResult, "scannerGetList failed: unknown result");
            }

            public void scannerClose(int id)
            {
                send_scannerClose(id);
                recv_scannerClose();
            }

            public void send_scannerClose(int id)
            {
                oprot_.WriteMessageBegin(new TMessage("scannerClose", TMessageType.Call, seqid_));
                scannerClose_args args = new scannerClose_args();
                args.Id = id;
                args.Write(oprot_);
                oprot_.WriteMessageEnd();
                oprot_.Transport.Flush();
            }

            public void recv_scannerClose()
            {
                TMessage msg = iprot_.ReadMessageBegin();
                if (msg.Type == TMessageType.Exception)
                {
                    TApplicationException x = TApplicationException.Read(iprot_);
                    iprot_.ReadMessageEnd();
                    throw x;
                }
                scannerClose_result result = new scannerClose_result();
                result.Read(iprot_);
                iprot_.ReadMessageEnd();
                if (result.__isset.io)
                {
                    throw result.Io;
                }
                if (result.__isset.ia)
                {
                    throw result.Ia;
                }
                return;
            }

        }
        public class Processor : TProcessor
        {
            public Processor(Iface iface)
            {
                iface_ = iface;
                processMap_["enableTable"] = enableTable_Process;
                processMap_["disableTable"] = disableTable_Process;
                processMap_["isTableEnabled"] = isTableEnabled_Process;
                processMap_["compact"] = compact_Process;
                processMap_["majorCompact"] = majorCompact_Process;
                processMap_["getTableNames"] = getTableNames_Process;
                processMap_["getColumnDescriptors"] = getColumnDescriptors_Process;
                processMap_["getTableRegions"] = getTableRegions_Process;
                processMap_["createTable"] = createTable_Process;
                processMap_["deleteTable"] = deleteTable_Process;
                processMap_["get"] = get_Process;
                processMap_["getVer"] = getVer_Process;
                processMap_["getVerTs"] = getVerTs_Process;
                processMap_["getRow"] = getRow_Process;
                processMap_["getRowWithColumns"] = getRowWithColumns_Process;
                processMap_["getRowTs"] = getRowTs_Process;
                processMap_["getRowWithColumnsTs"] = getRowWithColumnsTs_Process;
                processMap_["getRows"] = getRows_Process;
                processMap_["getRowsWithColumns"] = getRowsWithColumns_Process;
                processMap_["getRowsTs"] = getRowsTs_Process;
                processMap_["getRowsWithColumnsTs"] = getRowsWithColumnsTs_Process;
                processMap_["mutateRow"] = mutateRow_Process;
                processMap_["mutateRowTs"] = mutateRowTs_Process;
                processMap_["mutateRows"] = mutateRows_Process;
                processMap_["mutateRowsTs"] = mutateRowsTs_Process;
                processMap_["atomicIncrement"] = atomicIncrement_Process;
                processMap_["deleteAll"] = deleteAll_Process;
                processMap_["deleteAllTs"] = deleteAllTs_Process;
                processMap_["deleteAllRow"] = deleteAllRow_Process;
                processMap_["deleteAllRowTs"] = deleteAllRowTs_Process;
                processMap_["scannerOpenWithScan"] = scannerOpenWithScan_Process;
                processMap_["scannerOpen"] = scannerOpen_Process;
                processMap_["scannerOpenWithStop"] = scannerOpenWithStop_Process;
                processMap_["scannerOpenWithPrefix"] = scannerOpenWithPrefix_Process;
                processMap_["scannerOpenTs"] = scannerOpenTs_Process;
                processMap_["scannerOpenWithStopTs"] = scannerOpenWithStopTs_Process;
                processMap_["scannerGet"] = scannerGet_Process;
                processMap_["scannerGetList"] = scannerGetList_Process;
                processMap_["scannerClose"] = scannerClose_Process;
            }

            protected delegate void ProcessFunction(int seqid, TProtocol iprot, TProtocol oprot);
            private Iface iface_;
            protected Dictionary<string, ProcessFunction> processMap_ = new Dictionary<string, ProcessFunction>();

            public bool Process(TProtocol iprot, TProtocol oprot)
            {
                try
                {
                    TMessage msg = iprot.ReadMessageBegin();
                    ProcessFunction fn;
                    processMap_.TryGetValue(msg.Name, out fn);
                    if (fn == null)
                    {
                        TProtocolUtil.Skip(iprot, TType.Struct);
                        iprot.ReadMessageEnd();
                        TApplicationException x = new TApplicationException(TApplicationException.ExceptionType.UnknownMethod, "Invalid method name: '" + msg.Name + "'");
                        oprot.WriteMessageBegin(new TMessage(msg.Name, TMessageType.Exception, msg.SeqID));
                        x.Write(oprot);
                        oprot.WriteMessageEnd();
                        oprot.Transport.Flush();
                        return true;
                    }
                    fn(msg.SeqID, iprot, oprot);
                }
                catch (IOException)
                {
                    return false;
                }
                return true;
            }

            public void enableTable_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                enableTable_args args = new enableTable_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                enableTable_result result = new enableTable_result();
                try
                {
                    iface_.enableTable(args.TableName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("enableTable", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void disableTable_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                disableTable_args args = new disableTable_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                disableTable_result result = new disableTable_result();
                try
                {
                    iface_.disableTable(args.TableName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("disableTable", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void isTableEnabled_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                isTableEnabled_args args = new isTableEnabled_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                isTableEnabled_result result = new isTableEnabled_result();
                try
                {
                    result.Success = iface_.isTableEnabled(args.TableName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("isTableEnabled", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void compact_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                compact_args args = new compact_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                compact_result result = new compact_result();
                try
                {
                    iface_.compact(args.TableNameOrRegionName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("compact", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void majorCompact_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                majorCompact_args args = new majorCompact_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                majorCompact_result result = new majorCompact_result();
                try
                {
                    iface_.majorCompact(args.TableNameOrRegionName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("majorCompact", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getTableNames_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getTableNames_args args = new getTableNames_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getTableNames_result result = new getTableNames_result();
                try
                {
                    result.Success = iface_.getTableNames();
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getTableNames", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getColumnDescriptors_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getColumnDescriptors_args args = new getColumnDescriptors_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getColumnDescriptors_result result = new getColumnDescriptors_result();
                try
                {
                    result.Success = iface_.getColumnDescriptors(args.TableName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getColumnDescriptors", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getTableRegions_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getTableRegions_args args = new getTableRegions_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getTableRegions_result result = new getTableRegions_result();
                try
                {
                    result.Success = iface_.getTableRegions(args.TableName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getTableRegions", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void createTable_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                createTable_args args = new createTable_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                createTable_result result = new createTable_result();
                try
                {
                    iface_.createTable(args.TableName, args.ColumnFamilies);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                catch (AlreadyExists exist)
                {
                    result.Exist = exist;
                }
                oprot.WriteMessageBegin(new TMessage("createTable", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void deleteTable_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                deleteTable_args args = new deleteTable_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                deleteTable_result result = new deleteTable_result();
                try
                {
                    iface_.deleteTable(args.TableName);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("deleteTable", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void get_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                get_args args = new get_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                get_result result = new get_result();
                try
                {
                    result.Success = iface_.get(args.TableName, args.Row, args.Column);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("get", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getVer_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getVer_args args = new getVer_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getVer_result result = new getVer_result();
                try
                {
                    result.Success = iface_.getVer(args.TableName, args.Row, args.Column, args.NumVersions);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getVer", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getVerTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getVerTs_args args = new getVerTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getVerTs_result result = new getVerTs_result();
                try
                {
                    result.Success = iface_.getVerTs(args.TableName, args.Row, args.Column, args.Timestamp, args.NumVersions);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getVerTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRow_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRow_args args = new getRow_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRow_result result = new getRow_result();
                try
                {
                    result.Success = iface_.getRow(args.TableName, args.Row);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRow", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRowWithColumns_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRowWithColumns_args args = new getRowWithColumns_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRowWithColumns_result result = new getRowWithColumns_result();
                try
                {
                    result.Success = iface_.getRowWithColumns(args.TableName, args.Row, args.Columns);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRowWithColumns", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRowTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRowTs_args args = new getRowTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRowTs_result result = new getRowTs_result();
                try
                {
                    result.Success = iface_.getRowTs(args.TableName, args.Row, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRowTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRowWithColumnsTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRowWithColumnsTs_args args = new getRowWithColumnsTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRowWithColumnsTs_result result = new getRowWithColumnsTs_result();
                try
                {
                    result.Success = iface_.getRowWithColumnsTs(args.TableName, args.Row, args.Columns, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRowWithColumnsTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRows_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRows_args args = new getRows_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRows_result result = new getRows_result();
                try
                {
                    result.Success = iface_.getRows(args.TableName, args.Rows);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRows", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRowsWithColumns_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRowsWithColumns_args args = new getRowsWithColumns_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRowsWithColumns_result result = new getRowsWithColumns_result();
                try
                {
                    result.Success = iface_.getRowsWithColumns(args.TableName, args.Rows, args.Columns);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRowsWithColumns", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRowsTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRowsTs_args args = new getRowsTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRowsTs_result result = new getRowsTs_result();
                try
                {
                    result.Success = iface_.getRowsTs(args.TableName, args.Rows, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRowsTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void getRowsWithColumnsTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                getRowsWithColumnsTs_args args = new getRowsWithColumnsTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                getRowsWithColumnsTs_result result = new getRowsWithColumnsTs_result();
                try
                {
                    result.Success = iface_.getRowsWithColumnsTs(args.TableName, args.Rows, args.Columns, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("getRowsWithColumnsTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void mutateRow_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                mutateRow_args args = new mutateRow_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                mutateRow_result result = new mutateRow_result();
                try
                {
                    iface_.mutateRow(args.TableName, args.Row, args.Mutations);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("mutateRow", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void mutateRowTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                mutateRowTs_args args = new mutateRowTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                mutateRowTs_result result = new mutateRowTs_result();
                try
                {
                    iface_.mutateRowTs(args.TableName, args.Row, args.Mutations, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("mutateRowTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void mutateRows_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                mutateRows_args args = new mutateRows_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                mutateRows_result result = new mutateRows_result();
                try
                {
                    iface_.mutateRows(args.TableName, args.RowBatches);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("mutateRows", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void mutateRowsTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                mutateRowsTs_args args = new mutateRowsTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                mutateRowsTs_result result = new mutateRowsTs_result();
                try
                {
                    iface_.mutateRowsTs(args.TableName, args.RowBatches, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("mutateRowsTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void atomicIncrement_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                atomicIncrement_args args = new atomicIncrement_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                atomicIncrement_result result = new atomicIncrement_result();
                try
                {
                    result.Success = iface_.atomicIncrement(args.TableName, args.Row, args.Column, args.Value);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("atomicIncrement", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void deleteAll_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                deleteAll_args args = new deleteAll_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                deleteAll_result result = new deleteAll_result();
                try
                {
                    iface_.deleteAll(args.TableName, args.Row, args.Column);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("deleteAll", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void deleteAllTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                deleteAllTs_args args = new deleteAllTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                deleteAllTs_result result = new deleteAllTs_result();
                try
                {
                    iface_.deleteAllTs(args.TableName, args.Row, args.Column, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("deleteAllTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void deleteAllRow_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                deleteAllRow_args args = new deleteAllRow_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                deleteAllRow_result result = new deleteAllRow_result();
                try
                {
                    iface_.deleteAllRow(args.TableName, args.Row);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("deleteAllRow", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void deleteAllRowTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                deleteAllRowTs_args args = new deleteAllRowTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                deleteAllRowTs_result result = new deleteAllRowTs_result();
                try
                {
                    iface_.deleteAllRowTs(args.TableName, args.Row, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("deleteAllRowTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerOpenWithScan_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerOpenWithScan_args args = new scannerOpenWithScan_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerOpenWithScan_result result = new scannerOpenWithScan_result();
                try
                {
                    result.Success = iface_.scannerOpenWithScan(args.TableName, args.Scan);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("scannerOpenWithScan", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerOpen_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerOpen_args args = new scannerOpen_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerOpen_result result = new scannerOpen_result();
                try
                {
                    result.Success = iface_.scannerOpen(args.TableName, args.StartRow, args.Columns);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("scannerOpen", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerOpenWithStop_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerOpenWithStop_args args = new scannerOpenWithStop_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerOpenWithStop_result result = new scannerOpenWithStop_result();
                try
                {
                    result.Success = iface_.scannerOpenWithStop(args.TableName, args.StartRow, args.StopRow, args.Columns);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("scannerOpenWithStop", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerOpenWithPrefix_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerOpenWithPrefix_args args = new scannerOpenWithPrefix_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerOpenWithPrefix_result result = new scannerOpenWithPrefix_result();
                try
                {
                    result.Success = iface_.scannerOpenWithPrefix(args.TableName, args.StartAndPrefix, args.Columns);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("scannerOpenWithPrefix", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerOpenTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerOpenTs_args args = new scannerOpenTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerOpenTs_result result = new scannerOpenTs_result();
                try
                {
                    result.Success = iface_.scannerOpenTs(args.TableName, args.StartRow, args.Columns, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("scannerOpenTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerOpenWithStopTs_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerOpenWithStopTs_args args = new scannerOpenWithStopTs_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerOpenWithStopTs_result result = new scannerOpenWithStopTs_result();
                try
                {
                    result.Success = iface_.scannerOpenWithStopTs(args.TableName, args.StartRow, args.StopRow, args.Columns, args.Timestamp);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                oprot.WriteMessageBegin(new TMessage("scannerOpenWithStopTs", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerGet_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerGet_args args = new scannerGet_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerGet_result result = new scannerGet_result();
                try
                {
                    result.Success = iface_.scannerGet(args.Id);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("scannerGet", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerGetList_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerGetList_args args = new scannerGetList_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerGetList_result result = new scannerGetList_result();
                try
                {
                    result.Success = iface_.scannerGetList(args.Id, args.NbRows);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("scannerGetList", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

            public void scannerClose_Process(int seqid, TProtocol iprot, TProtocol oprot)
            {
                scannerClose_args args = new scannerClose_args();
                args.Read(iprot);
                iprot.ReadMessageEnd();
                scannerClose_result result = new scannerClose_result();
                try
                {
                    iface_.scannerClose(args.Id);
                }
                catch (IOError io)
                {
                    result.Io = io;
                }
                catch (IllegalArgument ia)
                {
                    result.Ia = ia;
                }
                oprot.WriteMessageBegin(new TMessage("scannerClose", TMessageType.Reply, seqid));
                result.Write(oprot);
                oprot.WriteMessageEnd();
                oprot.Transport.Flush();
            }

        }


        [Serializable]
        public partial class enableTable_args : TBase
        {
            private byte[] _tableName;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
            }

            public enableTable_args()
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
                                TableName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("enableTable_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("enableTable_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class enableTable_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public enableTable_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("enableTable_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("enableTable_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class disableTable_args : TBase
        {
            private byte[] _tableName;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
            }

            public disableTable_args()
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
                                TableName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("disableTable_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("disableTable_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class disableTable_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public disableTable_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("disableTable_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("disableTable_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class isTableEnabled_args : TBase
        {
            private byte[] _tableName;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
            }

            public isTableEnabled_args()
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
                                TableName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("isTableEnabled_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("isTableEnabled_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class isTableEnabled_result : TBase
        {
            private bool _success;
            private IOError _io;

            public bool Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public isTableEnabled_result()
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
                        case 0:
                            if (field.Type == TType.Bool)
                            {
                                Success = iprot.ReadBool();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("isTableEnabled_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.Bool;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBool(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("isTableEnabled_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class compact_args : TBase
        {
            private byte[] _tableNameOrRegionName;

            public byte[] TableNameOrRegionName
            {
                get
                {
                    return _tableNameOrRegionName;
                }
                set
                {
                    __isset.tableNameOrRegionName = true;
                    this._tableNameOrRegionName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableNameOrRegionName;
            }

            public compact_args()
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
                                TableNameOrRegionName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("compact_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableNameOrRegionName != null && __isset.tableNameOrRegionName)
                {
                    field.Name = "tableNameOrRegionName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableNameOrRegionName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("compact_args(");
                sb.Append("TableNameOrRegionName: ");
                sb.Append(TableNameOrRegionName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class compact_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public compact_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("compact_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("compact_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class majorCompact_args : TBase
        {
            private byte[] _tableNameOrRegionName;

            public byte[] TableNameOrRegionName
            {
                get
                {
                    return _tableNameOrRegionName;
                }
                set
                {
                    __isset.tableNameOrRegionName = true;
                    this._tableNameOrRegionName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableNameOrRegionName;
            }

            public majorCompact_args()
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
                                TableNameOrRegionName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("majorCompact_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableNameOrRegionName != null && __isset.tableNameOrRegionName)
                {
                    field.Name = "tableNameOrRegionName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableNameOrRegionName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("majorCompact_args(");
                sb.Append("TableNameOrRegionName: ");
                sb.Append(TableNameOrRegionName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class majorCompact_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public majorCompact_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("majorCompact_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("majorCompact_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getTableNames_args : TBase
        {

            public getTableNames_args()
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
                TStruct struc = new TStruct("getTableNames_args");
                oprot.WriteStructBegin(struc);
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getTableNames_args(");
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getTableNames_result : TBase
        {
            private List<byte[]> _success;
            private IOError _io;

            public List<byte[]> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getTableNames_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<byte[]>();
                                    TList _list13 = iprot.ReadListBegin();
                                    for (int _i14 = 0; _i14 < _list13.Count; ++_i14)
                                    {
                                        byte[] _elem15 = null;
                                        _elem15 = iprot.ReadBinary();
                                        Success.Add(_elem15);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getTableNames_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.String, Success.Count));
                            foreach (byte[] _iter16 in Success)
                            {
                                oprot.WriteBinary(_iter16);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getTableNames_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getColumnDescriptors_args : TBase
        {
            private byte[] _tableName;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
            }

            public getColumnDescriptors_args()
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
                                TableName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("getColumnDescriptors_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getColumnDescriptors_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getColumnDescriptors_result : TBase
        {
            private Dictionary<byte[], ColumnDescriptor> _success;
            private IOError _io;

            public Dictionary<byte[], ColumnDescriptor> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getColumnDescriptors_result()
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
                        case 0:
                            if (field.Type == TType.Map)
                            {
                                {
                                    Success = new Dictionary<byte[], ColumnDescriptor>();
                                    TMap _map17 = iprot.ReadMapBegin();
                                    for (int _i18 = 0; _i18 < _map17.Count; ++_i18)
                                    {
                                        byte[] _key19;
                                        ColumnDescriptor _val20;
                                        _key19 = iprot.ReadBinary();
                                        _val20 = new ColumnDescriptor();
                                        _val20.Read(iprot);
                                        Success[_key19] = _val20;
                                    }
                                    iprot.ReadMapEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getColumnDescriptors_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.Map;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteMapBegin(new TMap(TType.String, TType.Struct, Success.Count));
                            foreach (byte[] _iter21 in Success.Keys)
                            {
                                oprot.WriteBinary(_iter21);
                                Success[_iter21].Write(oprot);
                            }
                            oprot.WriteMapEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getColumnDescriptors_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getTableRegions_args : TBase
        {
            private byte[] _tableName;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
            }

            public getTableRegions_args()
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
                                TableName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("getTableRegions_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getTableRegions_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getTableRegions_result : TBase
        {
            private List<TRegionInfo> _success;
            private IOError _io;

            public List<TRegionInfo> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getTableRegions_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRegionInfo>();
                                    TList _list22 = iprot.ReadListBegin();
                                    for (int _i23 = 0; _i23 < _list22.Count; ++_i23)
                                    {
                                        TRegionInfo _elem24 = new TRegionInfo();
                                        _elem24 = new TRegionInfo();
                                        _elem24.Read(iprot);
                                        Success.Add(_elem24);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getTableRegions_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRegionInfo _iter25 in Success)
                            {
                                _iter25.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getTableRegions_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class createTable_args : TBase
        {
            private byte[] _tableName;
            private List<ColumnDescriptor> _columnFamilies;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<ColumnDescriptor> ColumnFamilies
            {
                get
                {
                    return _columnFamilies;
                }
                set
                {
                    __isset.columnFamilies = true;
                    this._columnFamilies = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool columnFamilies;
            }

            public createTable_args()
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
                                TableName = iprot.ReadBinary();
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
                                    ColumnFamilies = new List<ColumnDescriptor>();
                                    TList _list26 = iprot.ReadListBegin();
                                    for (int _i27 = 0; _i27 < _list26.Count; ++_i27)
                                    {
                                        ColumnDescriptor _elem28 = new ColumnDescriptor();
                                        _elem28 = new ColumnDescriptor();
                                        _elem28.Read(iprot);
                                        ColumnFamilies.Add(_elem28);
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
                TStruct struc = new TStruct("createTable_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (ColumnFamilies != null && __isset.columnFamilies)
                {
                    field.Name = "columnFamilies";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.Struct, ColumnFamilies.Count));
                        foreach (ColumnDescriptor _iter29 in ColumnFamilies)
                        {
                            _iter29.Write(oprot);
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
                StringBuilder sb = new StringBuilder("createTable_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",ColumnFamilies: ");
                sb.Append(ColumnFamilies);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class createTable_result : TBase
        {
            private IOError _io;
            private IllegalArgument _ia;
            private AlreadyExists _exist;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }

            public AlreadyExists Exist
            {
                get
                {
                    return _exist;
                }
                set
                {
                    __isset.exist = true;
                    this._exist = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
                public bool ia;
                public bool exist;
            }

            public createTable_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.Struct)
                            {
                                Exist = new AlreadyExists();
                                Exist.Read(iprot);
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
                TStruct struc = new TStruct("createTable_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.exist)
                {
                    if (Exist != null)
                    {
                        field.Name = "Exist";
                        field.Type = TType.Struct;
                        field.ID = 3;
                        oprot.WriteFieldBegin(field);
                        Exist.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("createTable_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(",Exist: ");
                sb.Append(Exist == null ? "<null>" : Exist.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteTable_args : TBase
        {
            private byte[] _tableName;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
            }

            public deleteTable_args()
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
                                TableName = iprot.ReadBinary();
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
                TStruct struc = new TStruct("deleteTable_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteTable_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteTable_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public deleteTable_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("deleteTable_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteTable_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class get_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private byte[] _column;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public byte[] Column
            {
                get
                {
                    return _column;
                }
                set
                {
                    __isset.column = true;
                    this._column = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool column;
            }

            public get_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                Column = iprot.ReadBinary();
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
                TStruct struc = new TStruct("get_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Column != null && __isset.column)
                {
                    field.Name = "column";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Column);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("get_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Column: ");
                sb.Append(Column);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class get_result : TBase
        {
            private List<TCell> _success;
            private IOError _io;

            public List<TCell> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public get_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TCell>();
                                    TList _list30 = iprot.ReadListBegin();
                                    for (int _i31 = 0; _i31 < _list30.Count; ++_i31)
                                    {
                                        TCell _elem32 = new TCell();
                                        _elem32 = new TCell();
                                        _elem32.Read(iprot);
                                        Success.Add(_elem32);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("get_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TCell _iter33 in Success)
                            {
                                _iter33.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("get_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getVer_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private byte[] _column;
            private int _numVersions;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public byte[] Column
            {
                get
                {
                    return _column;
                }
                set
                {
                    __isset.column = true;
                    this._column = value;
                }
            }

            public int NumVersions
            {
                get
                {
                    return _numVersions;
                }
                set
                {
                    __isset.numVersions = true;
                    this._numVersions = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool column;
                public bool numVersions;
            }

            public getVer_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                Column = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I32)
                            {
                                NumVersions = iprot.ReadI32();
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
                TStruct struc = new TStruct("getVer_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Column != null && __isset.column)
                {
                    field.Name = "column";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Column);
                    oprot.WriteFieldEnd();
                }
                if (__isset.numVersions)
                {
                    field.Name = "numVersions";
                    field.Type = TType.I32;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(NumVersions);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getVer_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Column: ");
                sb.Append(Column);
                sb.Append(",NumVersions: ");
                sb.Append(NumVersions);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getVer_result : TBase
        {
            private List<TCell> _success;
            private IOError _io;

            public List<TCell> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getVer_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TCell>();
                                    TList _list34 = iprot.ReadListBegin();
                                    for (int _i35 = 0; _i35 < _list34.Count; ++_i35)
                                    {
                                        TCell _elem36 = new TCell();
                                        _elem36 = new TCell();
                                        _elem36.Read(iprot);
                                        Success.Add(_elem36);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getVer_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TCell _iter37 in Success)
                            {
                                _iter37.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getVer_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getVerTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private byte[] _column;
            private long _timestamp;
            private int _numVersions;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public byte[] Column
            {
                get
                {
                    return _column;
                }
                set
                {
                    __isset.column = true;
                    this._column = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }

            public int NumVersions
            {
                get
                {
                    return _numVersions;
                }
                set
                {
                    __isset.numVersions = true;
                    this._numVersions = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool column;
                public bool timestamp;
                public bool numVersions;
            }

            public getVerTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                Column = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 5:
                            if (field.Type == TType.I32)
                            {
                                NumVersions = iprot.ReadI32();
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
                TStruct struc = new TStruct("getVerTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Column != null && __isset.column)
                {
                    field.Name = "column";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Column);
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                if (__isset.numVersions)
                {
                    field.Name = "numVersions";
                    field.Type = TType.I32;
                    field.ID = 5;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(NumVersions);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getVerTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Column: ");
                sb.Append(Column);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(",NumVersions: ");
                sb.Append(NumVersions);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getVerTs_result : TBase
        {
            private List<TCell> _success;
            private IOError _io;

            public List<TCell> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getVerTs_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TCell>();
                                    TList _list38 = iprot.ReadListBegin();
                                    for (int _i39 = 0; _i39 < _list38.Count; ++_i39)
                                    {
                                        TCell _elem40 = new TCell();
                                        _elem40 = new TCell();
                                        _elem40.Read(iprot);
                                        Success.Add(_elem40);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getVerTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TCell _iter41 in Success)
                            {
                                _iter41.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getVerTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRow_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
            }

            public getRow_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
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
                TStruct struc = new TStruct("getRow_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRow_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRow_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRow_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list42 = iprot.ReadListBegin();
                                    for (int _i43 = 0; _i43 < _list42.Count; ++_i43)
                                    {
                                        TRowResult _elem44 = new TRowResult();
                                        _elem44 = new TRowResult();
                                        _elem44.Read(iprot);
                                        Success.Add(_elem44);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRow_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter45 in Success)
                            {
                                _iter45.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRow_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowWithColumns_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private List<byte[]> _columns;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool columns;
            }

            public getRowWithColumns_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list46 = iprot.ReadListBegin();
                                    for (int _i47 = 0; _i47 < _list46.Count; ++_i47)
                                    {
                                        byte[] _elem48 = null;
                                        _elem48 = iprot.ReadBinary();
                                        Columns.Add(_elem48);
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
                TStruct struc = new TStruct("getRowWithColumns_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter49 in Columns)
                        {
                            oprot.WriteBinary(_iter49);
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
                StringBuilder sb = new StringBuilder("getRowWithColumns_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowWithColumns_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRowWithColumns_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list50 = iprot.ReadListBegin();
                                    for (int _i51 = 0; _i51 < _list50.Count; ++_i51)
                                    {
                                        TRowResult _elem52 = new TRowResult();
                                        _elem52 = new TRowResult();
                                        _elem52.Read(iprot);
                                        Success.Add(_elem52);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRowWithColumns_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter53 in Success)
                            {
                                _iter53.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowWithColumns_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool timestamp;
            }

            public getRowTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("getRowTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowTs_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRowTs_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list54 = iprot.ReadListBegin();
                                    for (int _i55 = 0; _i55 < _list54.Count; ++_i55)
                                    {
                                        TRowResult _elem56 = new TRowResult();
                                        _elem56 = new TRowResult();
                                        _elem56.Read(iprot);
                                        Success.Add(_elem56);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRowTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter57 in Success)
                            {
                                _iter57.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowWithColumnsTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private List<byte[]> _columns;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool columns;
                public bool timestamp;
            }

            public getRowWithColumnsTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list58 = iprot.ReadListBegin();
                                    for (int _i59 = 0; _i59 < _list58.Count; ++_i59)
                                    {
                                        byte[] _elem60 = null;
                                        _elem60 = iprot.ReadBinary();
                                        Columns.Add(_elem60);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("getRowWithColumnsTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter61 in Columns)
                        {
                            oprot.WriteBinary(_iter61);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowWithColumnsTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowWithColumnsTs_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRowWithColumnsTs_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list62 = iprot.ReadListBegin();
                                    for (int _i63 = 0; _i63 < _list62.Count; ++_i63)
                                    {
                                        TRowResult _elem64 = new TRowResult();
                                        _elem64 = new TRowResult();
                                        _elem64.Read(iprot);
                                        Success.Add(_elem64);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRowWithColumnsTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter65 in Success)
                            {
                                _iter65.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowWithColumnsTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRows_args : TBase
        {
            private byte[] _tableName;
            private List<byte[]> _rows;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<byte[]> Rows
            {
                get
                {
                    return _rows;
                }
                set
                {
                    __isset.rows = true;
                    this._rows = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool rows;
            }

            public getRows_args()
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
                                TableName = iprot.ReadBinary();
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
                                    Rows = new List<byte[]>();
                                    TList _list66 = iprot.ReadListBegin();
                                    for (int _i67 = 0; _i67 < _list66.Count; ++_i67)
                                    {
                                        byte[] _elem68 = null;
                                        _elem68 = iprot.ReadBinary();
                                        Rows.Add(_elem68);
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
                TStruct struc = new TStruct("getRows_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Rows != null && __isset.rows)
                {
                    field.Name = "rows";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Rows.Count));
                        foreach (byte[] _iter69 in Rows)
                        {
                            oprot.WriteBinary(_iter69);
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
                StringBuilder sb = new StringBuilder("getRows_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Rows: ");
                sb.Append(Rows);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRows_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRows_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list70 = iprot.ReadListBegin();
                                    for (int _i71 = 0; _i71 < _list70.Count; ++_i71)
                                    {
                                        TRowResult _elem72 = new TRowResult();
                                        _elem72 = new TRowResult();
                                        _elem72.Read(iprot);
                                        Success.Add(_elem72);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRows_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter73 in Success)
                            {
                                _iter73.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRows_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowsWithColumns_args : TBase
        {
            private byte[] _tableName;
            private List<byte[]> _rows;
            private List<byte[]> _columns;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<byte[]> Rows
            {
                get
                {
                    return _rows;
                }
                set
                {
                    __isset.rows = true;
                    this._rows = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool rows;
                public bool columns;
            }

            public getRowsWithColumns_args()
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
                                TableName = iprot.ReadBinary();
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
                                    Rows = new List<byte[]>();
                                    TList _list74 = iprot.ReadListBegin();
                                    for (int _i75 = 0; _i75 < _list74.Count; ++_i75)
                                    {
                                        byte[] _elem76 = null;
                                        _elem76 = iprot.ReadBinary();
                                        Rows.Add(_elem76);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list77 = iprot.ReadListBegin();
                                    for (int _i78 = 0; _i78 < _list77.Count; ++_i78)
                                    {
                                        byte[] _elem79 = null;
                                        _elem79 = iprot.ReadBinary();
                                        Columns.Add(_elem79);
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
                TStruct struc = new TStruct("getRowsWithColumns_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Rows != null && __isset.rows)
                {
                    field.Name = "rows";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Rows.Count));
                        foreach (byte[] _iter80 in Rows)
                        {
                            oprot.WriteBinary(_iter80);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter81 in Columns)
                        {
                            oprot.WriteBinary(_iter81);
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
                StringBuilder sb = new StringBuilder("getRowsWithColumns_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Rows: ");
                sb.Append(Rows);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowsWithColumns_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRowsWithColumns_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list82 = iprot.ReadListBegin();
                                    for (int _i83 = 0; _i83 < _list82.Count; ++_i83)
                                    {
                                        TRowResult _elem84 = new TRowResult();
                                        _elem84 = new TRowResult();
                                        _elem84.Read(iprot);
                                        Success.Add(_elem84);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRowsWithColumns_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter85 in Success)
                            {
                                _iter85.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowsWithColumns_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowsTs_args : TBase
        {
            private byte[] _tableName;
            private List<byte[]> _rows;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<byte[]> Rows
            {
                get
                {
                    return _rows;
                }
                set
                {
                    __isset.rows = true;
                    this._rows = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool rows;
                public bool timestamp;
            }

            public getRowsTs_args()
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
                                TableName = iprot.ReadBinary();
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
                                    Rows = new List<byte[]>();
                                    TList _list86 = iprot.ReadListBegin();
                                    for (int _i87 = 0; _i87 < _list86.Count; ++_i87)
                                    {
                                        byte[] _elem88 = null;
                                        _elem88 = iprot.ReadBinary();
                                        Rows.Add(_elem88);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("getRowsTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Rows != null && __isset.rows)
                {
                    field.Name = "rows";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Rows.Count));
                        foreach (byte[] _iter89 in Rows)
                        {
                            oprot.WriteBinary(_iter89);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowsTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Rows: ");
                sb.Append(Rows);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowsTs_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRowsTs_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list90 = iprot.ReadListBegin();
                                    for (int _i91 = 0; _i91 < _list90.Count; ++_i91)
                                    {
                                        TRowResult _elem92 = new TRowResult();
                                        _elem92 = new TRowResult();
                                        _elem92.Read(iprot);
                                        Success.Add(_elem92);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRowsTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter93 in Success)
                            {
                                _iter93.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowsTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowsWithColumnsTs_args : TBase
        {
            private byte[] _tableName;
            private List<byte[]> _rows;
            private List<byte[]> _columns;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<byte[]> Rows
            {
                get
                {
                    return _rows;
                }
                set
                {
                    __isset.rows = true;
                    this._rows = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool rows;
                public bool columns;
                public bool timestamp;
            }

            public getRowsWithColumnsTs_args()
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
                                TableName = iprot.ReadBinary();
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
                                    Rows = new List<byte[]>();
                                    TList _list94 = iprot.ReadListBegin();
                                    for (int _i95 = 0; _i95 < _list94.Count; ++_i95)
                                    {
                                        byte[] _elem96 = null;
                                        _elem96 = iprot.ReadBinary();
                                        Rows.Add(_elem96);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list97 = iprot.ReadListBegin();
                                    for (int _i98 = 0; _i98 < _list97.Count; ++_i98)
                                    {
                                        byte[] _elem99 = null;
                                        _elem99 = iprot.ReadBinary();
                                        Columns.Add(_elem99);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("getRowsWithColumnsTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Rows != null && __isset.rows)
                {
                    field.Name = "rows";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Rows.Count));
                        foreach (byte[] _iter100 in Rows)
                        {
                            oprot.WriteBinary(_iter100);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter101 in Columns)
                        {
                            oprot.WriteBinary(_iter101);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowsWithColumnsTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Rows: ");
                sb.Append(Rows);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class getRowsWithColumnsTs_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public getRowsWithColumnsTs_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list102 = iprot.ReadListBegin();
                                    for (int _i103 = 0; _i103 < _list102.Count; ++_i103)
                                    {
                                        TRowResult _elem104 = new TRowResult();
                                        _elem104 = new TRowResult();
                                        _elem104.Read(iprot);
                                        Success.Add(_elem104);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("getRowsWithColumnsTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter105 in Success)
                            {
                                _iter105.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("getRowsWithColumnsTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRow_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private List<Mutation> _mutations;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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
                public bool tableName;
                public bool row;
                public bool mutations;
            }

            public mutateRow_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Mutations = new List<Mutation>();
                                    TList _list106 = iprot.ReadListBegin();
                                    for (int _i107 = 0; _i107 < _list106.Count; ++_i107)
                                    {
                                        Mutation _elem108 = new Mutation();
                                        _elem108 = new Mutation();
                                        _elem108.Read(iprot);
                                        Mutations.Add(_elem108);
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
                TStruct struc = new TStruct("mutateRow_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Mutations != null && __isset.mutations)
                {
                    field.Name = "mutations";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.Struct, Mutations.Count));
                        foreach (Mutation _iter109 in Mutations)
                        {
                            _iter109.Write(oprot);
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
                StringBuilder sb = new StringBuilder("mutateRow_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Mutations: ");
                sb.Append(Mutations);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRow_result : TBase
        {
            private IOError _io;
            private IllegalArgument _ia;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
                public bool ia;
            }

            public mutateRow_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("mutateRow_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("mutateRow_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRowTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private List<Mutation> _mutations;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool mutations;
                public bool timestamp;
            }

            public mutateRowTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Mutations = new List<Mutation>();
                                    TList _list110 = iprot.ReadListBegin();
                                    for (int _i111 = 0; _i111 < _list110.Count; ++_i111)
                                    {
                                        Mutation _elem112 = new Mutation();
                                        _elem112 = new Mutation();
                                        _elem112.Read(iprot);
                                        Mutations.Add(_elem112);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("mutateRowTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Mutations != null && __isset.mutations)
                {
                    field.Name = "mutations";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.Struct, Mutations.Count));
                        foreach (Mutation _iter113 in Mutations)
                        {
                            _iter113.Write(oprot);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("mutateRowTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Mutations: ");
                sb.Append(Mutations);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRowTs_result : TBase
        {
            private IOError _io;
            private IllegalArgument _ia;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
                public bool ia;
            }

            public mutateRowTs_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("mutateRowTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("mutateRowTs_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRows_args : TBase
        {
            private byte[] _tableName;
            private List<BatchMutation> _rowBatches;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<BatchMutation> RowBatches
            {
                get
                {
                    return _rowBatches;
                }
                set
                {
                    __isset.rowBatches = true;
                    this._rowBatches = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool rowBatches;
            }

            public mutateRows_args()
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
                                TableName = iprot.ReadBinary();
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
                                    RowBatches = new List<BatchMutation>();
                                    TList _list114 = iprot.ReadListBegin();
                                    for (int _i115 = 0; _i115 < _list114.Count; ++_i115)
                                    {
                                        BatchMutation _elem116 = new BatchMutation();
                                        _elem116 = new BatchMutation();
                                        _elem116.Read(iprot);
                                        RowBatches.Add(_elem116);
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
                TStruct struc = new TStruct("mutateRows_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (RowBatches != null && __isset.rowBatches)
                {
                    field.Name = "rowBatches";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.Struct, RowBatches.Count));
                        foreach (BatchMutation _iter117 in RowBatches)
                        {
                            _iter117.Write(oprot);
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
                StringBuilder sb = new StringBuilder("mutateRows_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",RowBatches: ");
                sb.Append(RowBatches);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRows_result : TBase
        {
            private IOError _io;
            private IllegalArgument _ia;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
                public bool ia;
            }

            public mutateRows_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("mutateRows_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("mutateRows_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRowsTs_args : TBase
        {
            private byte[] _tableName;
            private List<BatchMutation> _rowBatches;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public List<BatchMutation> RowBatches
            {
                get
                {
                    return _rowBatches;
                }
                set
                {
                    __isset.rowBatches = true;
                    this._rowBatches = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool rowBatches;
                public bool timestamp;
            }

            public mutateRowsTs_args()
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
                                TableName = iprot.ReadBinary();
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
                                    RowBatches = new List<BatchMutation>();
                                    TList _list118 = iprot.ReadListBegin();
                                    for (int _i119 = 0; _i119 < _list118.Count; ++_i119)
                                    {
                                        BatchMutation _elem120 = new BatchMutation();
                                        _elem120 = new BatchMutation();
                                        _elem120.Read(iprot);
                                        RowBatches.Add(_elem120);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("mutateRowsTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (RowBatches != null && __isset.rowBatches)
                {
                    field.Name = "rowBatches";
                    field.Type = TType.List;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.Struct, RowBatches.Count));
                        foreach (BatchMutation _iter121 in RowBatches)
                        {
                            _iter121.Write(oprot);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("mutateRowsTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",RowBatches: ");
                sb.Append(RowBatches);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class mutateRowsTs_result : TBase
        {
            private IOError _io;
            private IllegalArgument _ia;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
                public bool ia;
            }

            public mutateRowsTs_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("mutateRowsTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("mutateRowsTs_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class atomicIncrement_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private byte[] _column;
            private long _value;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public byte[] Column
            {
                get
                {
                    return _column;
                }
                set
                {
                    __isset.column = true;
                    this._column = value;
                }
            }

            public long Value
            {
                get
                {
                    return _value;
                }
                set
                {
                    __isset.value = true;
                    this._value = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool column;
                public bool value;
            }

            public atomicIncrement_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                Column = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Value = iprot.ReadI64();
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
                TStruct struc = new TStruct("atomicIncrement_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Column != null && __isset.column)
                {
                    field.Name = "column";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Column);
                    oprot.WriteFieldEnd();
                }
                if (__isset.value)
                {
                    field.Name = "value";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Value);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("atomicIncrement_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Column: ");
                sb.Append(Column);
                sb.Append(",Value: ");
                sb.Append(Value);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class atomicIncrement_result : TBase
        {
            private long _success;
            private IOError _io;
            private IllegalArgument _ia;

            public long Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
                public bool ia;
            }

            public atomicIncrement_result()
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
                        case 0:
                            if (field.Type == TType.I64)
                            {
                                Success = iprot.ReadI64();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("atomicIncrement_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I64;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("atomicIncrement_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAll_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private byte[] _column;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public byte[] Column
            {
                get
                {
                    return _column;
                }
                set
                {
                    __isset.column = true;
                    this._column = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool column;
            }

            public deleteAll_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                Column = iprot.ReadBinary();
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
                TStruct struc = new TStruct("deleteAll_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Column != null && __isset.column)
                {
                    field.Name = "column";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Column);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAll_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Column: ");
                sb.Append(Column);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAll_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public deleteAll_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("deleteAll_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAll_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAllTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private byte[] _column;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public byte[] Column
            {
                get
                {
                    return _column;
                }
                set
                {
                    __isset.column = true;
                    this._column = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool column;
                public bool timestamp;
            }

            public deleteAllTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                Column = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("deleteAllTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (Column != null && __isset.column)
                {
                    field.Name = "column";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Column);
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAllTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Column: ");
                sb.Append(Column);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAllTs_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public deleteAllTs_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("deleteAllTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAllTs_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAllRow_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
            }

            public deleteAllRow_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
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
                TStruct struc = new TStruct("deleteAllRow_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAllRow_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAllRow_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public deleteAllRow_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("deleteAllRow_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAllRow_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAllRowTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _row;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

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

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool row;
                public bool timestamp;
            }

            public deleteAllRowTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                Row = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("deleteAllRowTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Row != null && __isset.row)
                {
                    field.Name = "row";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(Row);
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAllRowTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Row: ");
                sb.Append(Row);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class deleteAllRowTs_result : TBase
        {
            private IOError _io;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
            }

            public deleteAllRowTs_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("deleteAllRowTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("deleteAllRowTs_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithScan_args : TBase
        {
            private byte[] _tableName;
            private TScan _scan;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public TScan Scan
            {
                get
                {
                    return _scan;
                }
                set
                {
                    __isset.scan = true;
                    this._scan = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool scan;
            }

            public scannerOpenWithScan_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Scan = new TScan();
                                Scan.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpenWithScan_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (Scan != null && __isset.scan)
                {
                    field.Name = "scan";
                    field.Type = TType.Struct;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    Scan.Write(oprot);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenWithScan_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",Scan: ");
                sb.Append(Scan == null ? "<null>" : Scan.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithScan_result : TBase
        {
            private int _success;
            private IOError _io;

            public int Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public scannerOpenWithScan_result()
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
                        case 0:
                            if (field.Type == TType.I32)
                            {
                                Success = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpenWithScan_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I32;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenWithScan_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpen_args : TBase
        {
            private byte[] _tableName;
            private byte[] _startRow;
            private List<byte[]> _columns;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public byte[] StartRow
            {
                get
                {
                    return _startRow;
                }
                set
                {
                    __isset.startRow = true;
                    this._startRow = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool startRow;
                public bool columns;
            }

            public scannerOpen_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                StartRow = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list122 = iprot.ReadListBegin();
                                    for (int _i123 = 0; _i123 < _list122.Count; ++_i123)
                                    {
                                        byte[] _elem124 = null;
                                        _elem124 = iprot.ReadBinary();
                                        Columns.Add(_elem124);
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
                TStruct struc = new TStruct("scannerOpen_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (StartRow != null && __isset.startRow)
                {
                    field.Name = "startRow";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StartRow);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter125 in Columns)
                        {
                            oprot.WriteBinary(_iter125);
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
                StringBuilder sb = new StringBuilder("scannerOpen_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",StartRow: ");
                sb.Append(StartRow);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpen_result : TBase
        {
            private int _success;
            private IOError _io;

            public int Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public scannerOpen_result()
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
                        case 0:
                            if (field.Type == TType.I32)
                            {
                                Success = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpen_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I32;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpen_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithStop_args : TBase
        {
            private byte[] _tableName;
            private byte[] _startRow;
            private byte[] _stopRow;
            private List<byte[]> _columns;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public byte[] StartRow
            {
                get
                {
                    return _startRow;
                }
                set
                {
                    __isset.startRow = true;
                    this._startRow = value;
                }
            }

            public byte[] StopRow
            {
                get
                {
                    return _stopRow;
                }
                set
                {
                    __isset.stopRow = true;
                    this._stopRow = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool startRow;
                public bool stopRow;
                public bool columns;
            }

            public scannerOpenWithStop_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                StartRow = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                StopRow = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list126 = iprot.ReadListBegin();
                                    for (int _i127 = 0; _i127 < _list126.Count; ++_i127)
                                    {
                                        byte[] _elem128 = null;
                                        _elem128 = iprot.ReadBinary();
                                        Columns.Add(_elem128);
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
                TStruct struc = new TStruct("scannerOpenWithStop_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (StartRow != null && __isset.startRow)
                {
                    field.Name = "startRow";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StartRow);
                    oprot.WriteFieldEnd();
                }
                if (StopRow != null && __isset.stopRow)
                {
                    field.Name = "stopRow";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StopRow);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter129 in Columns)
                        {
                            oprot.WriteBinary(_iter129);
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
                StringBuilder sb = new StringBuilder("scannerOpenWithStop_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",StartRow: ");
                sb.Append(StartRow);
                sb.Append(",StopRow: ");
                sb.Append(StopRow);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithStop_result : TBase
        {
            private int _success;
            private IOError _io;

            public int Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public scannerOpenWithStop_result()
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
                        case 0:
                            if (field.Type == TType.I32)
                            {
                                Success = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpenWithStop_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I32;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenWithStop_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithPrefix_args : TBase
        {
            private byte[] _tableName;
            private byte[] _startAndPrefix;
            private List<byte[]> _columns;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public byte[] StartAndPrefix
            {
                get
                {
                    return _startAndPrefix;
                }
                set
                {
                    __isset.startAndPrefix = true;
                    this._startAndPrefix = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool startAndPrefix;
                public bool columns;
            }

            public scannerOpenWithPrefix_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                StartAndPrefix = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list130 = iprot.ReadListBegin();
                                    for (int _i131 = 0; _i131 < _list130.Count; ++_i131)
                                    {
                                        byte[] _elem132 = null;
                                        _elem132 = iprot.ReadBinary();
                                        Columns.Add(_elem132);
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
                TStruct struc = new TStruct("scannerOpenWithPrefix_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (StartAndPrefix != null && __isset.startAndPrefix)
                {
                    field.Name = "startAndPrefix";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StartAndPrefix);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter133 in Columns)
                        {
                            oprot.WriteBinary(_iter133);
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
                StringBuilder sb = new StringBuilder("scannerOpenWithPrefix_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",StartAndPrefix: ");
                sb.Append(StartAndPrefix);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithPrefix_result : TBase
        {
            private int _success;
            private IOError _io;

            public int Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public scannerOpenWithPrefix_result()
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
                        case 0:
                            if (field.Type == TType.I32)
                            {
                                Success = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpenWithPrefix_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I32;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenWithPrefix_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _startRow;
            private List<byte[]> _columns;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public byte[] StartRow
            {
                get
                {
                    return _startRow;
                }
                set
                {
                    __isset.startRow = true;
                    this._startRow = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool startRow;
                public bool columns;
                public bool timestamp;
            }

            public scannerOpenTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                StartRow = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list134 = iprot.ReadListBegin();
                                    for (int _i135 = 0; _i135 < _list134.Count; ++_i135)
                                    {
                                        byte[] _elem136 = null;
                                        _elem136 = iprot.ReadBinary();
                                        Columns.Add(_elem136);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("scannerOpenTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (StartRow != null && __isset.startRow)
                {
                    field.Name = "startRow";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StartRow);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter137 in Columns)
                        {
                            oprot.WriteBinary(_iter137);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",StartRow: ");
                sb.Append(StartRow);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenTs_result : TBase
        {
            private int _success;
            private IOError _io;

            public int Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public scannerOpenTs_result()
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
                        case 0:
                            if (field.Type == TType.I32)
                            {
                                Success = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpenTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I32;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithStopTs_args : TBase
        {
            private byte[] _tableName;
            private byte[] _startRow;
            private byte[] _stopRow;
            private List<byte[]> _columns;
            private long _timestamp;

            public byte[] TableName
            {
                get
                {
                    return _tableName;
                }
                set
                {
                    __isset.tableName = true;
                    this._tableName = value;
                }
            }

            public byte[] StartRow
            {
                get
                {
                    return _startRow;
                }
                set
                {
                    __isset.startRow = true;
                    this._startRow = value;
                }
            }

            public byte[] StopRow
            {
                get
                {
                    return _stopRow;
                }
                set
                {
                    __isset.stopRow = true;
                    this._stopRow = value;
                }
            }

            public List<byte[]> Columns
            {
                get
                {
                    return _columns;
                }
                set
                {
                    __isset.columns = true;
                    this._columns = value;
                }
            }

            public long Timestamp
            {
                get
                {
                    return _timestamp;
                }
                set
                {
                    __isset.timestamp = true;
                    this._timestamp = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool tableName;
                public bool startRow;
                public bool stopRow;
                public bool columns;
                public bool timestamp;
            }

            public scannerOpenWithStopTs_args()
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
                                TableName = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.String)
                            {
                                StartRow = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 3:
                            if (field.Type == TType.String)
                            {
                                StopRow = iprot.ReadBinary();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 4:
                            if (field.Type == TType.List)
                            {
                                {
                                    Columns = new List<byte[]>();
                                    TList _list138 = iprot.ReadListBegin();
                                    for (int _i139 = 0; _i139 < _list138.Count; ++_i139)
                                    {
                                        byte[] _elem140 = null;
                                        _elem140 = iprot.ReadBinary();
                                        Columns.Add(_elem140);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 5:
                            if (field.Type == TType.I64)
                            {
                                Timestamp = iprot.ReadI64();
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
                TStruct struc = new TStruct("scannerOpenWithStopTs_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (TableName != null && __isset.tableName)
                {
                    field.Name = "tableName";
                    field.Type = TType.String;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(TableName);
                    oprot.WriteFieldEnd();
                }
                if (StartRow != null && __isset.startRow)
                {
                    field.Name = "startRow";
                    field.Type = TType.String;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StartRow);
                    oprot.WriteFieldEnd();
                }
                if (StopRow != null && __isset.stopRow)
                {
                    field.Name = "stopRow";
                    field.Type = TType.String;
                    field.ID = 3;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteBinary(StopRow);
                    oprot.WriteFieldEnd();
                }
                if (Columns != null && __isset.columns)
                {
                    field.Name = "columns";
                    field.Type = TType.List;
                    field.ID = 4;
                    oprot.WriteFieldBegin(field);
                    {
                        oprot.WriteListBegin(new TList(TType.String, Columns.Count));
                        foreach (byte[] _iter141 in Columns)
                        {
                            oprot.WriteBinary(_iter141);
                        }
                        oprot.WriteListEnd();
                    }
                    oprot.WriteFieldEnd();
                }
                if (__isset.timestamp)
                {
                    field.Name = "timestamp";
                    field.Type = TType.I64;
                    field.ID = 5;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI64(Timestamp);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenWithStopTs_args(");
                sb.Append("TableName: ");
                sb.Append(TableName);
                sb.Append(",StartRow: ");
                sb.Append(StartRow);
                sb.Append(",StopRow: ");
                sb.Append(StopRow);
                sb.Append(",Columns: ");
                sb.Append(Columns);
                sb.Append(",Timestamp: ");
                sb.Append(Timestamp);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerOpenWithStopTs_result : TBase
        {
            private int _success;
            private IOError _io;

            public int Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
            }

            public scannerOpenWithStopTs_result()
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
                        case 0:
                            if (field.Type == TType.I32)
                            {
                                Success = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
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
                TStruct struc = new TStruct("scannerOpenWithStopTs_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    field.Name = "Success";
                    field.Type = TType.I32;
                    field.ID = 0;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Success);
                    oprot.WriteFieldEnd();
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerOpenWithStopTs_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerGet_args : TBase
        {
            private int _id;

            public int Id
            {
                get
                {
                    return _id;
                }
                set
                {
                    __isset.id = true;
                    this._id = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool id;
            }

            public scannerGet_args()
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
                            if (field.Type == TType.I32)
                            {
                                Id = iprot.ReadI32();
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
                TStruct struc = new TStruct("scannerGet_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (__isset.id)
                {
                    field.Name = "id";
                    field.Type = TType.I32;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Id);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerGet_args(");
                sb.Append("Id: ");
                sb.Append(Id);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerGet_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;
            private IllegalArgument _ia;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
                public bool ia;
            }

            public scannerGet_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list142 = iprot.ReadListBegin();
                                    for (int _i143 = 0; _i143 < _list142.Count; ++_i143)
                                    {
                                        TRowResult _elem144 = new TRowResult();
                                        _elem144 = new TRowResult();
                                        _elem144.Read(iprot);
                                        Success.Add(_elem144);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("scannerGet_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter145 in Success)
                            {
                                _iter145.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerGet_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerGetList_args : TBase
        {
            private int _id;
            private int _nbRows;

            public int Id
            {
                get
                {
                    return _id;
                }
                set
                {
                    __isset.id = true;
                    this._id = value;
                }
            }

            public int NbRows
            {
                get
                {
                    return _nbRows;
                }
                set
                {
                    __isset.nbRows = true;
                    this._nbRows = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool id;
                public bool nbRows;
            }

            public scannerGetList_args()
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
                            if (field.Type == TType.I32)
                            {
                                Id = iprot.ReadI32();
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.I32)
                            {
                                NbRows = iprot.ReadI32();
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
                TStruct struc = new TStruct("scannerGetList_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (__isset.id)
                {
                    field.Name = "id";
                    field.Type = TType.I32;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Id);
                    oprot.WriteFieldEnd();
                }
                if (__isset.nbRows)
                {
                    field.Name = "nbRows";
                    field.Type = TType.I32;
                    field.ID = 2;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(NbRows);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerGetList_args(");
                sb.Append("Id: ");
                sb.Append(Id);
                sb.Append(",NbRows: ");
                sb.Append(NbRows);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerGetList_result : TBase
        {
            private List<TRowResult> _success;
            private IOError _io;
            private IllegalArgument _ia;

            public List<TRowResult> Success
            {
                get
                {
                    return _success;
                }
                set
                {
                    __isset.success = true;
                    this._success = value;
                }
            }

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool success;
                public bool io;
                public bool ia;
            }

            public scannerGetList_result()
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
                        case 0:
                            if (field.Type == TType.List)
                            {
                                {
                                    Success = new List<TRowResult>();
                                    TList _list146 = iprot.ReadListBegin();
                                    for (int _i147 = 0; _i147 < _list146.Count; ++_i147)
                                    {
                                        TRowResult _elem148 = new TRowResult();
                                        _elem148 = new TRowResult();
                                        _elem148.Read(iprot);
                                        Success.Add(_elem148);
                                    }
                                    iprot.ReadListEnd();
                                }
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 1:
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("scannerGetList_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.success)
                {
                    if (Success != null)
                    {
                        field.Name = "Success";
                        field.Type = TType.List;
                        field.ID = 0;
                        oprot.WriteFieldBegin(field);
                        {
                            oprot.WriteListBegin(new TList(TType.Struct, Success.Count));
                            foreach (TRowResult _iter149 in Success)
                            {
                                _iter149.Write(oprot);
                            }
                            oprot.WriteListEnd();
                        }
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerGetList_result(");
                sb.Append("Success: ");
                sb.Append(Success);
                sb.Append(",Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerClose_args : TBase
        {
            private int _id;

            public int Id
            {
                get
                {
                    return _id;
                }
                set
                {
                    __isset.id = true;
                    this._id = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool id;
            }

            public scannerClose_args()
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
                            if (field.Type == TType.I32)
                            {
                                Id = iprot.ReadI32();
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
                TStruct struc = new TStruct("scannerClose_args");
                oprot.WriteStructBegin(struc);
                TField field = new TField();
                if (__isset.id)
                {
                    field.Name = "id";
                    field.Type = TType.I32;
                    field.ID = 1;
                    oprot.WriteFieldBegin(field);
                    oprot.WriteI32(Id);
                    oprot.WriteFieldEnd();
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerClose_args(");
                sb.Append("Id: ");
                sb.Append(Id);
                sb.Append(")");
                return sb.ToString();
            }

        }


        [Serializable]
        public partial class scannerClose_result : TBase
        {
            private IOError _io;
            private IllegalArgument _ia;

            public IOError Io
            {
                get
                {
                    return _io;
                }
                set
                {
                    __isset.io = true;
                    this._io = value;
                }
            }

            public IllegalArgument Ia
            {
                get
                {
                    return _ia;
                }
                set
                {
                    __isset.ia = true;
                    this._ia = value;
                }
            }


            public Isset __isset;
            [Serializable]
            public struct Isset
            {
                public bool io;
                public bool ia;
            }

            public scannerClose_result()
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
                            if (field.Type == TType.Struct)
                            {
                                Io = new IOError();
                                Io.Read(iprot);
                            }
                            else
                            {
                                TProtocolUtil.Skip(iprot, field.Type);
                            }
                            break;
                        case 2:
                            if (field.Type == TType.Struct)
                            {
                                Ia = new IllegalArgument();
                                Ia.Read(iprot);
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
                TStruct struc = new TStruct("scannerClose_result");
                oprot.WriteStructBegin(struc);
                TField field = new TField();

                if (this.__isset.io)
                {
                    if (Io != null)
                    {
                        field.Name = "Io";
                        field.Type = TType.Struct;
                        field.ID = 1;
                        oprot.WriteFieldBegin(field);
                        Io.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                else if (this.__isset.ia)
                {
                    if (Ia != null)
                    {
                        field.Name = "Ia";
                        field.Type = TType.Struct;
                        field.ID = 2;
                        oprot.WriteFieldBegin(field);
                        Ia.Write(oprot);
                        oprot.WriteFieldEnd();
                    }
                }
                oprot.WriteFieldStop();
                oprot.WriteStructEnd();
            }

            public override string ToString()
            {
                StringBuilder sb = new StringBuilder("scannerClose_result(");
                sb.Append("Io: ");
                sb.Append(Io == null ? "<null>" : Io.ToString());
                sb.Append(",Ia: ");
                sb.Append(Ia == null ? "<null>" : Ia.ToString());
                sb.Append(")");
                return sb.ToString();
            }

        }

    }
}