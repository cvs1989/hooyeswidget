DROP PROC [Get_CoursesList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-19
-- Update date: 2013-02-25
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_CoursesList]
	@Year int =  2012,
	@Type int = -1
AS
	IF @Type = -1 
	SELECT * from Courses where [YEAR] = @Year ORDER BY Sort ASC
	ELSE
	SELECT * from Courses where [YEAR] = @Year and [type] = @Type ORDER BY Sort ASC
RETURN 0