DROP PROC [Get_MyCoursesList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-22
-- Update date: 2011-12-22
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCoursesList]
	@MID int
AS
	SELECT 
		 c.CID
		,c.Name
		,c.Year
		,Minutes = ISNULL(myc.Minutes,0)
		,Second = ISNULL(myc.Second,0)
		,Status = ISNULL(myc.Status,0)
	FROM Courses c
	left outer join (
		SELECT CID,[Minutes],[Second],[Status] FROM My_Courses
		WHERE MID = @MID ) myc on myc.CID= c.CID
RETURN 0