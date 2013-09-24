-- =============================================
-- Version:     1.0.0.1
-- Author:		hooyes
-- Create date: 2012-09-24
-- Update date: 2013-09-24
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyReport] 
   @MID INT = 0
AS 
    SELECT  [ID],
	        [MID] ,
            [Year] ,
            Score = ISNULL([Score], 0) ,
            Compulsory = ISNULL([Compulsory], 0) ,
            Elective = ISNULL([Elective], 0) ,
            [Status] = ISNULL([Status], 0) ,
			[Minutes] = ISNULL([Minutes],0),
            Memo = ISNULL([Memo], 0),
			CreateDate,
			UpdateDate,
			CommitDate
    FROM    [Report]
    WHERE   [MID] = @MID
	ORDER BY [Year] DESC
	      
	      
    RETURN 0