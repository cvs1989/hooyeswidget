-- DROP PROC [Get_Courses]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2011-12-18
-- Update date: 2012-02-18
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Get_Courses]
	@CID int,
	@MID int = 0,
	@Year int = 2012,
	@Type int = 0
AS
	IF @MID = 0 
	BEGIN
	SELECT * FROM Courses WHERE CID = @CID
	END
	ELSE
	BEGIN
	SELECT c.*
			,CateX =  CASE  
				 WHEN @Type = 0 and c.Cate = 100 THEN  0
				 WHEN @Type = 0 and c.Cate = 101 THEN  1
				 WHEN @Type = 1 and c.Cate = 100 THEN  1
				 WHEN @Type = 1 and c.Cate = 101 THEN  0
				ELSE c.Cate
          END
	FROM Courses c WHERE CID = @CID
	END
RETURN 0