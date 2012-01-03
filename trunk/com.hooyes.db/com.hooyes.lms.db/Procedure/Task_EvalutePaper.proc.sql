DROP PROC [Task_EvalutePaper]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-02
-- Update date: 2012-01-03
-- Desc:
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvalutePaper]
	@MID int = 0
AS
	/* 1.判题是否正确 */
	DECLARE  @QID int
			,@Answer nvarchar(50)
	DECLARE CusCursor CURSOR FOR
	SELECT QID,Answer FROM MY_Question WHERE MID = @MID
	OPEN CusCursor 
    FETCH NEXT FROM CusCursor INTO @QID,@Answer
    WHILE (@@FETCH_STATUS = 0)
	BEGIN
		IF EXISTS(
		  SELECT * FROM Question where QID = @QID and Answer = @Answer
		)
		BEGIN
			UPDATE My_Question SET Status = 1 WHERE QID = @QID and MID = @MID 
		END
	   ELSE
	    BEGIN
			UPDATE My_Question SET Status = 0 WHERE QID = @QID and MID = @MID 
		END

	FETCH NEXT FROM CusCursor INTO @QID,@Answer
    END
	CLOSE CusCursor 
	DEALLOCATE CusCursor 
	/* 2. 评分 */
	DECLARE @a float
	DECLARE @b float
	DECLARE @c float
	DECLARE @Score int
	SELECT @a = count(status)  FROM dbo.My_Question  WHERE MID = @MID and Status= 0
	SELECT @b = count(status)  FROM dbo.My_Question  WHERE MID = @MID and Status= 1
	SET @c = @b / (@a + @b) 
	SET @Score = @c * 100
	EXECUTE [Update_Report] @MID, @Score


RETURN 0