DROP PROC [M_Get_MessageQueue]
GO
-- =============================================
-- Version:     2.0.0.1
-- Author:		hooyes
-- Create date: 2013-01-27
-- Update date: 2014-07-28
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[M_Get_MessageQueue]
    @Flag INT = 0 ,
    @DayID INT = 0 ,
    @Rows INT = 10
AS 
    SELECT TOP ( @Rows )
            a.* ,
            b.[Message]
    FROM    [MessageQueue] a
            INNER JOIN [MessageContent] b ON a.MsgID = b.MsgID
    WHERE   a.Flag = @Flag
            AND ( @DayID = 0
                  OR a.DayID = @DayID
                )
    RETURN 0
