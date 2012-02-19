DROP PROC [Get_MyPaper]
GO
-- =============================================
-- Author:		hooyes
-- Create date: 2012-01-02
-- Update date: 2012-02-18
-- Desc: @Type 用户类型 0 行政事业类 1 企业类
-- =============================================
CREATE PROCEDURE [dbo].[Get_MyPaper]
	@MID int = 0,
	@Year int = 0,
	@Type int = 0 
AS
	 DECLARE @Question TABLE  (
		[QID] [int] NOT NULL,
		[CID] [int] NOT NULL,
		[Subject] [nvarchar](200) NOT NULL,
		[A] [nvarchar](100) NULL,
		[B] [nvarchar](100) NULL,
		[C] [nvarchar](100) NULL,
		[D] [nvarchar](100) NULL,
		[Answer] [nvarchar](50) NOT NULL,
		[Score] [int] NOT NULL,
		[Cate] [int] NULL)

	DECLARE  @CID1 int = 6001
			,@CID2 int = 6003
	IF @Type = 0
	BEGIN
		SET @CID1 = 6001
		SET @CID2 = 6003
	END
	IF @Type = 1
	BEGIN
		SET @CID1 = 6001
		SET @CID2 = 6004
	END

	INSERT INTO @Question([QID],[CID],[Subject],[A],[B],[C],[D],[Answer],[Score],[Cate])
	SELECT TOP 5 * FROM Question WHERE CID = @CID1 and Cate = 1
	ORDER BY NEWID()

	INSERT INTO @Question([QID],[CID],[Subject],[A],[B],[C],[D],[Answer],[Score],[Cate])
	SELECT TOP 5 * FROM Question WHERE CID = @CID1 and Cate = 3
	ORDER BY NEWID()


	INSERT INTO @Question([QID],[CID],[Subject],[A],[B],[C],[D],[Answer],[Score],[Cate])
	SELECT TOP 5 * FROM Question WHERE CID = @CID2 and Cate = 1
	ORDER BY NEWID()

	INSERT INTO @Question([QID],[CID],[Subject],[A],[B],[C],[D],[Answer],[Score],[Cate])
	SELECT TOP 5 * FROM Question WHERE CID = @CID2 and Cate = 3
	ORDER BY NEWID()
	
	SELECT * FROM @Question order by Cate asc

 
RETURN 0