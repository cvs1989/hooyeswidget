DROP PROC [Task_EvaluteContents]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-02-18
-- Update date: 2012-03-03
-- Desc: 评估一门课程是否可以取得学时
-- =============================================
CREATE PROCEDURE [dbo].[Task_EvaluteContents]
	@MID int, 
	@CID int
AS
	DECLARE  @count1 int = 0
			,@count2 int
	/* 完成所有章节 @count1 = 0 */
	/*
		SELECT @count1 = COUNT(1) FROM (
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
		) as co
		WHERE Status = 0 
	*/
	/* 分钟数已完成 @count2 > 0 */
	SELECT @count2 = COUNT(1) FROM My_Courses myc 
		inner join Courses c on c.CID = myc.CID
	WHERE myc.MID = @MID 
		and myc.CID = @CID
		and myc.Minutes > c.ActMinutes


	/* 满足此2条件，课程完成 */

	IF @count1 = 0 and @count2 >0
	BEGIN
		UPDATE My_Courses SET Status = 1 WHERE CID = @CID and MID = @MID 
	END
	ELSE
	BEGIN
	--- 已置为完成的 不受影响
		UPDATE My_Courses SET 
		
		[Status] = CASE 
					   WHEN [Status] > 0 THEN [Status]
					   ELSE 0 
				   END
		
		 WHERE CID = @CID and MID = @MID 
	END

	

RETURN 0