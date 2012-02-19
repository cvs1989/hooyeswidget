DROP PROC [Get_CoursesList]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-19
-- Update date: 2012-02-19
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_CoursesList]
	@Year int =  2012,
	@Type int = -1
AS
	IF @Type = -1 
	SELECT * from Courses where [YEAR] = @Year
	ELSE
	SELECT * from Courses where [YEAR] = @Year and [type] = @Type
RETURN 0