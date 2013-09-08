-- DROP PROC [Get_MyCoursesList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-22
-- Update date: 2012-02-19
-- Desc:
-- @Type 0 行政 1 企业
-- Cate  0 选修 1 必修  
-- Cate  100 行政选修,企业必修   101 行政必修,企业选修
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyCoursesList]
	@MID int,
	@Year int = 2012,
	@Type int = 0
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
		,c.Sort
		,c.Teacher
		,c.Length
		,Cate =  CASE  
				 WHEN @Type = 0 and c.Cate = 100 THEN  0
				 WHEN @Type = 0 and c.Cate = 101 THEN  1
				 WHEN @Type = 1 and c.Cate = 100 THEN  1
				 WHEN @Type = 1 and c.Cate = 101 THEN  0
				ELSE c.Cate
          END
		,oCate = c.Cate
	FROM Courses c
	LEFT OUTER JOIN (
		SELECT CID,[Minutes],[Second],[Status] FROM My_Courses
		WHERE MID = @MID ) myc on myc.CID = c.CID 
	WHERE c.Year = @Year
		and (c.Type = @Type or c.Type = 3)
	) as tb
	order by Cate desc,Sort asc,CID asc
RETURN 0