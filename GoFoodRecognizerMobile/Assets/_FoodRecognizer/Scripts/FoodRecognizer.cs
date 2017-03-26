using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FoodRecognizer : MonoBehaviour {
    public static string SERVER = "http://10.17.10.161/frcms/index.php/"; // 202.51.126.3
	SimpleJSON.JSONNode currentImageUploaded = null;
	SimpleJSON.JSONNode currentRecognized = null;
	// Use this for initialization
	void Start ()
    {
		WebCamTexture webcamTexture = new WebCamTexture();
		webcamTexture.requestedWidth = 860;
		webcamTexture.requestedHeight = 480;
		Renderer renderer = GetComponent<Renderer>();
		renderer.material.mainTexture = webcamTexture;
		webcamTexture.Play();

        Debug.Log(SystemInfo.deviceUniqueIdentifier);
        StartCoroutine(AutoRegisterDevice());
	}
	
	// Update is called once per frame
	void Update () {
		
	}

    public void PutImage()
    {
		StartCoroutine(IEPutImage());
    }

    IEnumerator IEPutImage()
    {
        //OpenCVForUnitySample.WebCamTextureToMatSample capturedFrame = FindObjectOfType<OpenCVForUnitySample.WebCamTextureToMatSample>();
        
        //OpenCVForUnity.Mat m = capturedFrame.GetCurrentFrame();
        
		Texture2D tex = new Texture2D(Screen.width, Screen.height, TextureFormat.RGB24, false);
		tex.ReadPixels(new Rect(0, 0, Screen.width, Screen.height), 0, 0);
		tex.Apply();

        if (tex != null)
        {
            //OpenCVForUnity.Utils.texture2DToMat(tex, m);
            //int w = m.width() / 2;
            //int h = m.height() / 2;
            //OpenCVForUnity.Mat m2 = new OpenCVForUnity.Mat(h, w, OpenCVForUnity.CvType.CV_8UC4);
            //OpenCVForUnity.Imgproc.resize(m, m2, new OpenCVForUnity.Size(w, h));
            //OpenCVForUnity.Imgproc.cvtColor(m2, m2, OpenCVForUnity.Imgproc.COLOR_BGRA2GRAY);
            //Texture2D tex = new Texture2D(w, h, TextureFormat.RGB24, false);
            //OpenCVForUnity.Utils.matToTexture2D(m2, tex);
            WWWForm wf = new WWWForm();
            wf.AddField("sn", SystemInfo.deviceUniqueIdentifier);
            wf.AddBinaryData("userfile", tex.EncodeToJPG(), "face.jpg", "image/jpg");
            WWW www = new WWW(FoodRecognizer.SERVER + "api/put_image", wf);
            ShowObject(GameObject.Find("Loading"), true);
			ShowObject (GameObject.Find ("Button"), false);
            yield return www;
            if (string.IsNullOrEmpty(www.error))
            {
                Debug.Log(www.text);
                //this.ShowTextBox("Sending Image", www.text, 3f);
				currentImageUploaded = SimpleJSON.JSON.Parse(www.text);
				StartCoroutine (IERecognize());
            }
            else
            {
				ShowObject(GameObject.Find("Loading"), false);
                this.ShowTextBox("Sending Image", "Error :\n" + www.error, 3f);
                yield return new WaitForSeconds(3f);
            }
        }
    }

	IEnumerator IERecognize()
	{
		WWWForm wf = new WWWForm();
		wf.AddField("sn", SystemInfo.deviceUniqueIdentifier);
		wf.AddField ("id", currentImageUploaded ["id"].Value.ToString ());
		WWW www = new WWW(FoodRecognizer.SERVER + "/api/recognize", wf);
		yield return www;
		if (string.IsNullOrEmpty(www.error))
		{
			Debug.Log(www.text);
			//this.ShowTextBox("Sending Image", www.text, 3f);
			currentRecognized = SimpleJSON.JSON.Parse(www.text);
			if (!currentRecognized ["food_id"].AsInt.Equals (-1)) {
				//Debug.Log ("The Image Index is : " + currentRecognized ["food_id"].AsInt);
				Debug.Log ("The Food name is : " + currentRecognized ["name"].Value.ToString());
				this.ShowTextBox("Food Name", currentRecognized ["name"].Value.ToString(), 3f);
				ShowObject(GameObject.Find("Loading"), false);

				ShowObject (GameObject.Find ("Button"), true);
			} else {
				StartCoroutine (IERecognize());
			}
		}
		else
		{
			Debug.Log ("Recognize request failed. retrying...");
			//this.ShowTextBox("Recognizing...", "Error :\n" + www.error, 3f);
			yield return new WaitForSeconds(1f);
			StartCoroutine (IERecognize());
		}
	}

    IEnumerator AutoRegisterDevice()
    {
        WWWForm wf = new WWWForm();
        wf.AddField("sn", SystemInfo.deviceUniqueIdentifier);
        WWW w = new WWW(FoodRecognizer.SERVER + "api/dr", wf);
        yield return w;
        if(string.IsNullOrEmpty(w.error))
        {
            Debug.Log(w.text);
            //this.ShowTextBox("Registering Device Check", w.text, 3f);
            SimpleJSON.JSONNode result = SimpleJSON.JSON.Parse(w.text);
        }
        else
        {
            this.ShowTextBox("Registering Device Check", "Error :\n" + w.error, 3f);
            yield return new WaitForSeconds(3f);
            this.ShowTextBox("Registering Device Check", "Retrying...", 1f);
            StartCoroutine(AutoRegisterDevice());
        }
    }

    void ShowTextBox(string caption, string message, float time)
    {
        GameObject.Find("TextBox/Caption/Text").GetComponent<UnityEngine.UI.Text>().text = caption;
        GameObject.Find("TextBox/Text").GetComponent<UnityEngine.UI.Text>().text = message;
        StartCoroutine(IETextBox(time));
    }

    IEnumerator IETextBox(float time)
    {
        this.ShowObject(GameObject.Find("TextBox"), true);
        yield return new WaitForSeconds(time);
        this.ShowObject(GameObject.Find("TextBox"), false);
    }

    void ShowObject(GameObject go, bool state)
    {
        if (state)
            go.transform.localScale = Vector3.one;
        else
            go.transform.localScale = Vector3.zero;
    }
    
}
