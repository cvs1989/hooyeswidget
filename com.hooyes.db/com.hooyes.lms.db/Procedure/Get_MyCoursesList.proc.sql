DROP PROC [Get_MyCoursesList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-22
-- Update date: 2012-02-06
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCoursesList]
	@MID int,
	@Year int = 2012
AS
SELECT * FROM (
	SELECT 
		 c.CID
		,c.Name
		,c.Year
		,Minutes = ISNULL(myc.Minutes,0)
		,Second = ISNULL(myc.Second,0)
		,Status = ISNULL(myc.Status,0)
		,c.Type
		,c.Cate
		,c.Sort
		,c.Teacher
		,c.Length
	FROM Courses c
	left outer join (
		SELECT CID,[Minutes],[Second],[Status] FROM My_Courses
		WHERE MID = @MID ) myc on myc.CID= c.CID 
	WHERE c.Year = @Year
	) as tb
	order by Cate desc,Sort desc,CID asc
RETURN 0