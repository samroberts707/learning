# CraftCMS with AWS Assets

This is a trial install of CraftCMS that uses AWS S3 for storing assets and AWS CloudFront to serve the assets.

I'm using this tutorial as a guide but will have step by step notes below as an updated guide: https://nystudio107.com/blog/using-aws-s3-buckets-cloudfront-distribution-with-craft-cms#setting-up-s3

### Step One - Create S3 Bucket

Create an AWS S3 Bucket. For this example I'll be setting up the bucket called `nucreative-craft-test`.
Buckets should be unique to each project and follow the same naming convention, this will be as follows moving forward `PROJECT_NAME-bucket`.

When creating the bucket leave all options as default except the name and be sure to make sure **Block *all* public access** is selected. (This will be automatically updated when creating CloudFront Distribution)

### Step Two - Create IAM
IAM stands for *Identity and Access Management* this in short controls who can do what in a AWS object, such as an S3 Bucket.

We want to create a new IAM Policy and User for each client website so they can only access and edit their own content.

To do this go to Services -> IAM -> Policies -> **Create Policy**.

Click the JSON tab and paste in the following, being sure to replace `REPLACE-WITH-BUCKET-NAME` with the bucket name we just created.

<details>
<summary>JSON</summary>
<br>
<pre>
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "acm:ListCertificates",
                "cloudfront:GetDistribution",
                "cloudfront:GetStreamingDistribution",
                "cloudfront:GetDistributionConfig",
                "cloudfront:ListDistributions",
                "cloudfront:ListCloudFrontOriginAccessIdentities",
                "cloudfront:CreateInvalidation",
                "cloudfront:GetInvalidation",
                "cloudfront:ListInvalidations",
                "elasticloadbalancing:DescribeLoadBalancers",
                "iam:ListServerCertificates",
                "sns:ListSubscriptionsByTopic",
                "sns:ListTopics",
                "waf:GetWebACL",
                "waf:ListWebACLs"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "s3:GetBucketLocation",
                "s3:ListAllMyBuckets"
            ],
            "Resource": "*"
        },
        {
            "Effect": "Allow",
            "Action": [
                "s3:ListBucket"
            ],
            "Resource": [
                "arn:aws:s3:::REPLACE-WITH-BUCKET-NAME"
            ]
        },
        {
            "Effect": "Allow",
            "Action": [
                "s3:*"
            ],
            "Resource": [
                "arn:aws:s3:::REPLACE-WITH-BUCKET-NAME/*"
            ]
        }
    ]
}
</pre>
</details>
<br>
Once copied in and the bucket name replaced click through to **Tags** and then to **Review**. Here we create the policy name, I will be following this naming convention moving forward `PROJECT_NAME-policy`. Add a brief description and create the policy.


### Step Three - Create a IAM Group

Rather than setting the new policy we just made to a bucket or user we're going to attach it to a user group. This adds a bit of fallback in case a user credentials get lost. 

To do this navigatate to Services -> IAM -> User Groups > **Create Group**

Name the group using the following naming convention `PROJECT_NAME-group` and attach the policy we just created to the group. *For now don't add any users*.

### Step Four - Create a IAM User

Next we're going to create a user for this project, this user will give access to the S3 Bucket so we will need to remember its **Access Key ID** and **Secret access Key**. (*These are best stored in 1password, along with the CSV download*)

Create the user following this naming convention `PROJECT_NAME-user`, give it the **Programatic access** type. Then on the next screen tick the user group we just created. 

Once you create the user you will now be given these Access Keys, **THIS IS THE ONLY TIME TO GET THESE** so make sure to securely save them and the CSV download.

### Step Five - Create a CloudFront Distribution