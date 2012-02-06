DROP PROC [Get_MyCourses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-02-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCourses]
	 @MID int
	,@CID int
AS
	SELECT 
		a.CID
		,a.Name
		,Minutes = ISNULL(b.Minutes,0)
		,Second = ISNULL(b.Second,0)
		,Status = ISNULL(b.Status,0)
	FROM 
	(SELECT * FROM Courses WHERE CID = @CID) AS a
		LEFT OUTER JOIN 
	(SELECT *FROM My_Courses WHERE CID = @CID and MID = @MID ) AS b
	ON a.CID = b.CID
RETURN 0