DROP PROC [Task_EvaluteCourses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-15
-- Update date: 2012-02-29
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvaluteCourses]
	@MID int
AS
	DECLARE @Type int = 0
		   ,@Year int = 2012
		   ,@Compulsory decimal
		   ,@Elective decimal
	SELECT @Year = [Year],@Type = [Type] FROM Member WHERE MID = @MID
	SELECT
		@Compulsory = SUM(  CASE Cate WHEN 1 THEN  CLength ELSE 0 END )
		,@Elective  = SUM(  CASE Cate WHEN 0 THEN  GMinutes ELSE 0 END ) / 45
	FROM (
			SELECT 
				 c.CID
				,CLength = CASE
				   WHEN Status = 1 THEN c.Length
				  ELSE 0
				 END 
				,GMinutes = CASE
				   WHEN Status = 1 THEN c.Length * 45
				  ELSE ISNULL(myc.Minutes,0)
				 END 
				,c.Length
				,Cate =  CASE  
						 WHEN @Type = 0 and c.Cate = 100 THEN  0
						 WHEN @Type = 0 and c.Cate = 101 THEN  1
						 WHEN @Type = 1 and c.Cate = 100 THEN  1
						 WHEN @Type = 1 and c.Cate = 101 THEN  0
						 WHEN c.Cate = 99 THEN 0
						ELSE c.Cate
				  END
			FROM Courses c
			LEFT OUTER JOIN (
				SELECT CID,[Minutes],[Second],[Status] FROM My_Courses
				WHERE MID = @MID ) myc on myc.CID = c.CID 
			WHERE c.Year = @Year
				and (c.Type = @Type or c.Type = 3)
		) AS tb

		EXECUTE [Update_Report] 
		         @MID = @MID
				,@Compulsory  = @Compulsory
				,@Elective    = @Elective

RETURN 0