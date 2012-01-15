DROP PROC [Get_MyContents]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-14
-- Update date: 2012-01-15
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyContents]
	@MID int, 
	@CID int
AS
	SELECT c.CID
		,c.CCID
		,c.Name
		,c.Url
		,Minutes = ISNULL(myc.Minutes,0)
		,Second = ISNULL(myc.Second,0)
		,Status = ISNULL(myc.Status,0) 
	FROM (SELECT CID,CCID,Name,Url FROM Contents WHERE CID = @CID)
	as c
		LEFT OUTER JOIN
		(SELECT CID,CCID,MID,[Minutes],[Second],[Status] FROM My_Contents WHERE MID = @MID and CID = @CID)
	as myc
	ON myc.CCID = c.CCID

RETURN 0