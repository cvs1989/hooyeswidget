-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2013-09-29
-- Update date: 2013-09-29
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyProducts] @MID INT = 0
AS 
    SELECT  [ID] ,
            [MID] ,
            [PID] ,
            [CreateDate] ,
            [Memo]
    FROM    [My_Products]
    WHERE   MID = @MID
    RETURN 0
