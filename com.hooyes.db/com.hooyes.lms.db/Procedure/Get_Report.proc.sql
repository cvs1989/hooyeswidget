DROP PROC [Get_Report]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-03
-- Update date: 2012-02-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Report]
	@MID int = 0
AS
	SELECT [MID]
		  ,Score      = ISNULL([Score],0)
		  ,Compulsory = ISNULL([Compulsory],0)
		  ,Elective   = ISNULL([Elective],0)
		  ,Status     = ISNULL([Status],0)
		  ,Memo       = ISNULL([Memo],0)
	 FROM [Report]
	 WHERE [MID] = @MID
RETURN 0